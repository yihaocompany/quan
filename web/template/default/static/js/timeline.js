jQuery(document).ready(function($){var $timeline_block=$('.cd-timeline-block');$timeline_block.each(function(){if($(this).offset().top>$(window).scrollTop()+$(window).height()*0.75){$(this).find('.cd-timeline-images, .cd-timeline-content').addClass('is-hidden');}});$(window).on('scroll',function(){$timeline_block.each(function(){if($(this).offset().top<=$(window).scrollTop()+$(window).height()*0.75&&$(this).find('.cd-timeline-images').hasClass('is-hidden')){$(this).find('.cd-timeline-images, .cd-timeline-content').removeClass('is-hidden').addClass('bounce-in');}});});});