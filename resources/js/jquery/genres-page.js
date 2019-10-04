import $ from 'jquery';

let genreId = null;

$('.s-genre-item__label').click(function() {
  $(this).siblings('.s-genre-item__subgenres-link').fadeToggle('300');
});

$('.s-genre-item__subgenres-link').click(function() {
  genreId = $(this).attr('data-id');
});

$('.genre-selection-form').magnificPopup({
	type: 'inline',
	preloader: false,
	focus: '#name',

	callbacks: {
		beforeOpen: function() {
      $.ajax({
        method: "POST",
        url: "http://localhost:9090/graphql",
        contentType: "application/json",
        complete: function(data) {
          let checkboxes = $('.genre-selection ul li');
          if(data.fail.length > 0) {
            console.log('ошибка');
          } else if(data.responseJSON.data.genres.data.length === 0) {
            $('.genre-selection ul').append('<li>Список поджанров пуст</li>');
            $('.genre-selection').removeClass('loading');
            $('.genresForm__header').text()
          } else {
            data.responseJSON.data.genres.data.forEach((genre) => {
              $('.genre-selection ul').append('<li><span class="input-checkbox"><input value="' + genre.id + '" id="gnr' + genre.id +'" type="checkbox" name="remember"><label for="gnr' + genre.id + '">' + genre.name + '</label></span></li>')
            });
            let genreName = $('input[id="id' + genreId + '"]').siblings('label').find('span').text();
            $('.genresForm__header').text(genreName);
            $('.genre-selection').removeClass('loading');
            if(!$('input[id="id' + genreId + '"]').parent().hasClass('is-initialized')) {
              let checkboxes = $('.genresForm__list input:checkbox');
              checkboxes.each(function(i, elem) {
                $(elem).prop('checked', true);
              });
              let checked = $('.genresForm__list input:checked');
              checked.each(function(i, elem) {
                $('input[id="id' + genreId + '"]').parent().append('<input name="genres[]" value="' + $(elem).attr('value') + '" hidden type="checkbox">');
              });
              $('input[id="id' + genreId + '"]').parent().addClass('is-initialized');
            } else {
              console.log('has class');
              let checkboxes = $('input[id="id' + genreId + '"]').siblings('input[hidden]');
              checkboxes.each(function(i, elem) {
                $('.genresForm__list input:checkbox[value="' + $(elem).attr('value') + '"]').prop('checked', true);
              });
            }
          }
        },
        data: JSON.stringify({
          variables: {
            rootGenreId: genreId
          },
          query: `query ($rootGenreId: Int){
            genres(limit: 30, page: 1, rootGenreId: $rootGenreId){
              data{
                id
                name
              }
            }
          }`,
        })
      });

			if($(window).width() < 700) {
				this.st.focus = false;
			} else {
				this.st.focus = '#name';
			}
		},
    close: function() {
      $('.genresForm__list').empty();
      $('.genre-selection').addClass('loading');
    }
	}
});

$('.genres-deselect').click(function() {
  let checkboxes = $('.genresForm__list input:checkbox');
  checkboxes.each(function(i, elem) {
    $(elem).prop('checked', false);
  });
  $('input[id="id' + genreId + '"]').prop('checked', false);
  $('a.s-genre-item__subgenres-link[data-id="' + genreId + '"]').fadeOut('300');
});

$('.genres-select').click(function() {
  let hiddenInputs = $('input[id="id' + genreId + '"]').siblings('input[hidden]');
  hiddenInputs.each(function(i, elem) {
    $(elem).remove();
  });
  let checkboxes = $('.genresForm__list input:checked');
  checkboxes.each(function(i, elem) {
    $('input[id="id' + genreId + '"]').parent().append('<input name="genres[]" value="' + $(elem).attr('value') + '" hidden type="checkbox">');
  });
  if(checkboxes.length > 0) {
    $('a.s-genre-item__subgenres-link[data-id="' + genreId + '"]').fadeIn('300');
  };
  $('.genresForm__list').empty();
  $('.genre-selection').addClass('loading');
  $.magnificPopup.close();
});
