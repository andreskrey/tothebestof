var baseUrl = rootUrl;

var url = window.location.pathname;
var filename = url.substring(url.lastIndexOf('/') + 1);

var userUrl = baseUrl + 'user/';
var cookieUrl = userUrl + 'clear';
var suggestURL = baseUrl + 'ajax';


$('document').ready(function () {

    $('.date').datepicker({
            autoclose: true
        }
    );

    // FOCUS
    if (filename == 'playlist') {
        $('#PlaylistBand').focus();
    } else if (filename == 'contact') {
        $('#ContactNamemail').focus();
    } else if (filename == 'request-a-feature') {
        $('#FeatureDescription').focus();
    } else {
        $('#searchinput').focus();
    }
    ;

    // SEARCH TYPEAHEAD
    $('.search input').on('input', function (letters) {
        $('.tooltip').hide();
    });


    var searchType, searchTypeFinal;

    $(".search .selectBox").selectbox({
        onChange: function (val, inst) {
            searchTypeFinal = val;
            if (searchTypeFinal == 'playlist') {
                window.location.href = baseUrl + 'playlist';
                return;
            }

            $('#searchinput').autocomplete().setOptions({
                paramName: searchTypeFinal,
                params: {}
            });
        }
    });

    $('#searchinput').autocomplete({
        serviceUrl: suggestURL,
        type: "POST",
        minChars: 3,
        lookupLimit: 10,
        noCache: false,
        paramName: 'suggestBand',
        maxHeight: null,
        deferRequestBy: 200,
        transformResult: function (data) {
            response = $.parseJSON(data);
            return {
                suggestions: $.map(response.data.results, function (p) {
                    return { value: p.title, url: p.url};
                })
            };
        },
        onSelect: function (suggestion) {
            window.location.href = suggestion.url;
        },
        triggerSelectOnValidInput: false,
        showNoSuggestionNotice: true,
        noSuggestionNotice: 'No se enontraron resultados',
        appendTo: '.inputAhead'
    });


    $('.search form').submit(function (letters) {
        searchType = $('.sbSelector').text().toLowerCase();
        var searchInfo = $('.search form input').val();
        var actionUrl = baseUrl + searchType + '/' + searchInfo;
        $(this).attr("action", actionUrl).submit();
    });


    // PROFILE INFO
    $('.profileHeader .fireList').on('click', function (e) {
        e.preventDefault();
        var goToList = $(this).attr('href');
        $('.profileLists').hide();
        $(goToList).show();
    });


    // POPUP FORMS
    $('.fireForm').on('click', function (e) {
        e.preventDefault();
        var goToForm = $(this).attr('href');
        $('.cover').fadeIn();
        $('.formUser').hide();
        $('.formsUser,' + goToForm).show();
    });

    $('.closePopup, .cover').on('click', function () {
        $('.cover').fadeOut();
        $('.formsUser, .formUser').hide();
    });


    // COOL SELECT BOX
    $(".selectBox").selectbox();
    $(".sbHolder li:last a").css('border', '0');


    // TOOLTIPS
    $('.search .tooltip, .alert, .error-message').on('click', function () {
        $(this).hide();
    });

    $('.optionsBandUser a span.glyphicon, .donated a,  a.favIteminfo').on('mouseenter',function () {
        $(this).next().show();
    }).on('mouseleave', function () {
        $(this).next().hide();
    });

    // SELECT ALL IN PLAYLIST
    $('#PlaylistSelectAll').click(function (event) {
        if (this.checked) {
            $('.selectAllAble').each(function () {
                this.checked = true;
            });
        } else {
            $('.selectAllAble').each(function () {
                this.checked = false;
            });
        }
    });


// FAVOURITES
    $('.fav, .unfav').on('click', function (e) {
        e.preventDefault();
        $('.favoriteMessage.tooltip').fadeIn().delay(800).fadeOut(400);
        var favUnfav = $(this);
        var bandId = $(this).attr('data-band');
        var bandName = $(this).attr('data-name');
        var favAction = $(this).attr('class');

        $.ajax({
            url: userUrl + favAction + '/' + bandId,
            type: 'GET',
            success: function () {
                if ((favAction) == 'fav') {
                    alertMessage('Added to favorites', 'success');
                    $(favUnfav).removeClass('fav').addClass('unfav');
                    $('.toFav.tooltip').html('Already<br/>favorited');
                    $(".favList").append('<li>' + bandName + '</li>');

                } else {
                    alertMessage('Removed from favorites', 'success');
                    $(favUnfav).removeClass('unfav').addClass('fav');
                    $('.toFav.tooltip').html('Add to<br/>favorites');
                    $(".favList").find(":contains('" + bandName + "')").remove();
                }

            },
            error: function () {
                alertMessage('Sorry! Something Went Wrong!<br/> Please try again later', 'success');
            }
        });
    });

    // UNFAV DESDE INFO.CTP
    $('.directUnfav').on('click', function (e) {
        e.preventDefault();
        var bandId = $(this).attr('data-band');
        var favedBand = $(this).parent();
        console.log(favedBand);
        $.ajax({
            url: userUrl + 'unfav/' + bandId,
            type: 'GET',
            success: function () {
                alertMessage('Removed from favorites', 'success');
                $(favedBand).remove();
                var countFavs = $('#profileFavorites li').size();
                console.log(countFavs);
                if (countFavs < 1) {
                    $('#profileFavorites h3').text('No favourites');
                }

            },
            error: function () {
                alertMessage('Sorry! Something Went Wrong!<br/> Please try again later', 'success');
            }
        });
    });


// CLEAR BISCOTTI, ALSO BAPA DI BUPI
    $('.clearCookie').on('click', function (e) {
        e.preventDefault();
        $.ajax({
            url: cookieUrl,
            type: 'GET',
            success: function () {
                $('.searchHistory').hide();
                alertMessage('Your history has been deleted', 'success');
            },
            error: function () {
                alertMessage('Sorry! Something Went Wrong!<br/> Please try again later', 'error');
            }
        });
    });

    // SHOW/HIDE PARA EL FILTER DE STATS
    $('.formTrigger').on('click', function (e) {
        $('.statsForm').show();
    });

// COPY TO CLIPBOARD
    var client = new ZeroClipboard(document.getElementById("copyUrl"));
    client.on("ready", function (readyEvent) {
        client.on("aftercopy", function (event) {
            $('<div class="alert alert-success"><a href="#" data-dismiss="alert" class="close">×</a>Your playlist URL - ' + event.data["text/plain"] + ' - is on your clipboard</div>')
                .insertAfter("header").on('click', function () {
                    $(this).hide();
                });
        });
    });

// ALERT MESSAGE
    function alertMessage(message, status) {
        $('<div class="alert alert-' + status + '"><a href="#" data-dismiss="alert" class="close">×</a>' + message + '</div>')
            .insertAfter("header").on('click',function () {
                $(this).hide();
            }).delay(2000).fadeOut(400);
    }

})
;