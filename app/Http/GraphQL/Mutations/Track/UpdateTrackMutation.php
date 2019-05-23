<?php

namespace App\Http\GraphQL\Mutations\Track;

use App\Models\Track;
use Carbon\Carbon;
use GraphQL;
use GraphQL\Type\Definition\Type;
use PhpOffice\PhpWord\IOFactory;
use Rebing\GraphQL\Support\Mutation;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UpdateTrackMutation extends Mutation
{
    protected $attributes = [
        'name' => 'UpdateTrack',
        'description' => 'Обновление информации о треке',
    ];

    public function type()
    {
        return GraphQL::type('Track');
    }

    public function args()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Индетификатор трека',
            ],
            'infoTrack' => [
                'type' => \GraphQL::type('TrackInput'),
                'description' => 'Информация о треке',
            ],
        ];
    }

    public function rules(array $args = [])
    {
        return [
            'id' => ['required', function ($attribute, $value, $fail) {
                $user = \Auth::user();
                $track = Track::query()->find($value);
                if (true === empty($track)) {
                    $fail('Трек  не найден');
                }
                if ($track->user_id !== $user->id) {
                    $fail('Не достаточно прав на изменение информации треке');
                }
            }],
        ];
    }

    public function resolve($root, $args)
    {
        /** @var UploadedFile $file */
        $file = $args['infoTrack']['songText'];
        switch ($file->getClientMimeType()) {
            case 'text/plain':
                $text = file_get_contents($file);
                break;
            default:
                $phpWord = IOFactory::load($args['infoTrack']['songText']);
                $text = '';
                $sections = $phpWord->getSections();
                foreach ($sections as $key => $value) {
                    $sectionElement = $value->getElements();
                    foreach ($sectionElement as $elementKey => $elementValue) {
                        if ($elementValue instanceof \PhpOffice\PhpWord\Element\TextRun) {
                            $secondSectionElement = $elementValue->getElements();
                            foreach ($secondSectionElement as $secondSectionElementKey => $secondSectionElementValue) {
                                if ($secondSectionElementValue instanceof \PhpOffice\PhpWord\Element\Text) {
                                    $text .= "\n".$secondSectionElementValue->getText();
                                }
                            }
                        }
                    }
                }
                break;
        }

        $config = \HTMLPurifier_Config::createDefault();
        $purifier = new \HTMLPurifier($config);
        $clean_html = $purifier->purify($text);

        /** @var Track $track */
        $track = Track::query()->find($args['id']);
        $track->genres()->sync($args['infoTrack']['genres']);

        $track->update([
            'track_name' => $args['infoTrack']['trackName'],
            'album_id' => empty($args['infoTrack']['album']) ? null : $args['infoTrack']['album'],
            'singer' => $args['infoTrack']['singer'],
            'song_text' => $clean_html,
            'track_date' => Carbon::create($args['infoTrack']['trackDate'], 1, 1),
            'state' => 'fileload',
        ]);

        $track->save();

        return $track;
    }
}
