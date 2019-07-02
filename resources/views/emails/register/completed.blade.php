@extends('emails.email')

@section('content')



    <tr>
        <td>
            <table width="600" cellpadding="0" cellspacing="0" border="0" align="center">
                <tbody>
                <tr>
                    <td>
                        <table width="504" cellpadding="0" cellspacing="0" border="0" align="center" style="padding: 35px 0;">
                            <tbody>
                            <tr>
                                <td style="padding-bottom: 20px;">
                                    <h3 style="font-size: 24px; font-weight: 700; color: #2f2f2f; margin: 0 0 15px;">@lang('emails.register.thankForRegister')!</h3>
                                      <p style="font-size: 16px; line-height: 24px; color: #313131; margin: 0;">
                                        @lang('emails.register.text')
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="{{$linkToProfile}}" style="display: block; width: 208px; height: 40px; font-size: 14px; line-height: 40px; text-decoration: none; text-align: center; color: #fff; background-image: url('{{ env('APP_URL') }}/images/emails/icons/gradient-link.png');"> @lang('emails.register.fillProfile')</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                @if($star === true)
                <tr>
                    <td>
                        <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" style="padding-bottom: 40px;">
                            <tbody>
                            <tr>
                                <td>
                                    <a href="#" style="display: block; width: 600px; height: 203px; text-decoration: none; background-image: url('{{ env('APP_URL') }}/images/emails/img/upload_file.png');">
                                        <p style="font-size: 24px; font-weight: 700; line-height: 38px; text-align: center; color: #fff; margin: 0; padding: 40px 0 15px;">
                                            @lang('emails.register.share')
                                        </p>
                                        <p style="width: 328px; height: 48px; font-size: 14px; line-height: 48px; text-align: center; color: #fff; background-image: url('{{ env('APP_URL') }}/images/emails/img/border.png'); margin: 0 auto;">
                                            @lang('emails.register.uploadFile')
                                        </p>
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                @endif
                @if($playLists !== null)
                <tr>
                    <td>
                        <table width="504" cellpadding="0" cellspacing="0" border="0" align="center" style="padding: 0 0 35px 0;">
                            <tbody>
                            <tr>
                                <td style="padding-bottom: 20px;">
                                    <h3 style="font-size: 24px; font-weight: 700; color: #2f2f2f; margin: 0 0 15px;">@lang('emails.recommend')</h3>
                                    <p style="font-size: 12px; line-height: 14px; color: #606060; margin: 0;">
                                        @lang('emails.register.playlistRecommend')
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table width="504" cellpadding="0" cellspacing="0" border="0" align="center">
                                        <tbody>
                                        <tr>
                                            @foreach ($playLists as $list)
                                            <td style="width: 50%;">
                                                @if($list['link'])<a href="{{$list['link']}}" style="position: relative; display: block; text-decoration: none;">@endif
                                                    @if($list['list_img'])<img src="{{$list['list_img']}}" style="display: block; max-width: 100%; margin: 0 0 10px;">@endif
                                                    <p style="position: absolute; width: 120px; font-size: 12px; line-height: 14px; font-weight: 700; text-align: right; color: #fff; top: 10px; right: 20px; margin: 0;">
                                                        {{$list['name']}}<br>
                                                        <span style="font-size: 10px; line-height: 14px; color: #fff;">{{$list['date']}}</span>
                                                    </p>
                                                    <p style="position: absolute; width: 120px; font-size: 10px; line-height: 14px; font-weight: 700; text-align: right; color: #fff; bottom: 20px; right: 20px; margin: 0;">
                                                        {{$list['count_tracks']}}
                                                    </p>
                                                @if($list['link'])</a>@endif
                                            </td>
                                            @endforeach
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                @endif

                @if ($topList !== null)
                   <tr>
                       <td>
                           <table width="504" cellpadding="0" cellspacing="0" border="0" align="center" style="padding: 0 0 35px 0;">
                               <tbody>
                               <tr>
                                   <td style="padding-bottom: 20px;">
                                       <h3 style="font-size: 24px; font-weight: 700; color: #2f2f2f; margin: 0 0 15px;">@lang('emails.register.top') 50</h3>
                                       <p style="font-size: 12px; line-height: 14px; color: #606060; margin: 0;">
                                           @lang('emails.register.topMusicians')
                                       </p>
                                   </td>
                               </tr>
                               <tr>
                                   <td>
                                       <table width="504" cellpadding="0" cellspacing="0" border="0" align="center">
                                           <tbody>
                                           @foreach ($topList as $track)
                                           <tr>
                                               <td style="width: 15px; font-size: 12px; line-height: 14px; color: #606060; padding-bottom: 15px;">
                                                   {{$loop->iteration}}
                                               </td>
                                               <td style="width: 39px; padding: 0 0 15px 10px;">
                                                   @if($track['album_img'])<img src="{{$track['album_img']}}" style="width: 32px; border-radius: 3px;">@endif
                                               </td>
                                               <td style="width: 250px;  padding: 0 0 15px 55px;">
                                                   <p style="font-size: 12px; line-height: 14px; color: #231F20; margin: 0;"><b>{{$track['track_name']}}</b></p>
                                                   <p style="font-size: 10px; line-height: 12px; color: #606060; margin: 0;">{{$track['singer']}}</p>
                                               </td>
                                               <td style="width: 100px; font-size: 12px; line-height: 14px; text-align: center; color: #606060;  padding: 0 0 15px 35px;">
                                                   {{$track['track_time']}}
                                               </td>
                                           </tr>
                                           @endforeach
                                           </tbody>
                                       </table>
                                   </td>
                               </tr>
                               </tbody>
                           </table>
                       </td>
                   </tr>
                @endif
                @if($importantEvents !== null)
                   <tr>
                       <td>
                           <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" style="padding-bottom: 40px;">
                               <tbody>
                               @foreach ($importantEvents as $impEvent)
                                   @if ($loop->iteration%2 !== 0)
                                       <tr>
                                           @endif
                                           <td>
                                               <a href="{{$impEvent['url']}}" style="position: relative; display: block; text-decoration: none;">
                                                   <img style="display: block; max-width: 100%;" src="{{$impEvent['img']}}">
                                                   <p style="position: absolute; width: 330px; font-size: 24px; font-weight: 700; color: #fff; top: 25px; left: 50px; margin: 0;">{{$impEvent['name']}}</p>
                                               </a>
                                           </td>
                                           @if ($loop->iteration%2 === 0 || $loop->last)
                                       </tr>
                                   @endif
                               @endforeach
                               </tbody>
                           </table>
                       </td>
                   </tr>
                @endif
                   </tbody>
               </table>
            </td>
            </tr>
@endsection
