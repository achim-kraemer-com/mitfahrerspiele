/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.scss in this case)
import './styles/app.scss';
import bsCustomFileInput from 'bs-custom-file-input'

bsCustomFileInput.init();

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

const $ = require('jquery');
require('bootstrap');
require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');

$('.positive-content').on('click', () => {
    const url = $('.positive-content').data('url');
    const content = $('.positive-content').data('id');
    const data = {'content': content};
    $.ajax({
        type: "POST",
        cache: false,
        url: url,
        data: data,
        statusCode: {
            404: function () {
                alert("Bitte versuchen Sie nicht das System zu manipulieren");
            }
        },
        success: function (data) {
            $('#positive-content-value').text(data);
            stopVoting();
        },
    });
});

$('.negative-content').on('click', () => {
    const url = $('.negative-content').data('url');
    const content = $('.positive-content').data('id');
    const data = {'content': content};
    $.ajax({
        type: "POST",
        cache: false,
        url: url,
        data: data,
        statusCode: {
            404: function () {
                alert("Bitte versuchen Sie nicht das System zu manipulieren");
            }
        },
        success: function (data) {
            $('#negative-content-value').text(data);
            stopVoting();
        },
    });
});

function stopVoting()
{
    $('.positive-content').off('click').removeClass('positive-content');
    $('.negative-content').off('click').removeClass('negative-content');
}
