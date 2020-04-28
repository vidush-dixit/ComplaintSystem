$(document).ready(function() {
  $().ready(function() {
    $sidebar = $('.sidebar');

    $sidebar_img_container = $sidebar.find('.sidebar-background');

    $full_page = $('.full-page');

    $sidebar_responsive = $('body > .navbar-collapse');

    window_width = $(window).width();

    $('.fixed-plugin a').click(function(event) {
      // If we click on switch, stop propagation of the event, so the dropdown will not hide, otherwise set the section active
      if ($(this).hasClass('switch-trigger')) {
        if (event.stopPropagation) {
          event.stopPropagation();
        } else if (window.event) {
          window.event.cancelBubble = true;
        }
      }
    });

    $('.fixed-plugin .active-color span').click(function() {
      $full_page_background = $('.full-page-background');

      $(this).siblings().removeClass('active');
      $(this).addClass('active');

      var new_color = $(this).data('color');

      if ($sidebar.length != 0) {
        $sidebar.attr('data-color', new_color);
      }

      if ($full_page.length != 0) {
        $full_page.attr('filter-color', new_color);
      }

      if ($sidebar_responsive.length != 0) {
        $sidebar_responsive.attr('data-color', new_color);
      }
    });

    $('.fixed-plugin .background-color .badge').click(function() {
      $(this).siblings().removeClass('active');
      $(this).addClass('active');

      var new_color = $(this).data('background-color');

      if ($sidebar.length != 0) {
        $sidebar.attr('data-background-color', new_color);
      }
    });

    $('.fixed-plugin .img-holder').click(function() {
      $full_page_background = $('.full-page-background');

      $(this).parent('li').siblings().removeClass('active');
      $(this).parent('li').addClass('active');


      var new_image = $(this).find("img").attr('src');

      if ($sidebar_img_container.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
        $sidebar_img_container.fadeOut('fast', function() {
          $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
          $sidebar_img_container.fadeIn('fast');
        });
      }

      if ($full_page_background.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
        var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

        $full_page_background.fadeOut('fast', function() {
          $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
          $full_page_background.fadeIn('fast');
        });
      }

      if ($('.switch-sidebar-image input:checked').length == 0) {
        var new_image = $('.fixed-plugin li.active .img-holder').find("img").attr('src');
        var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

        $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
        $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
      }

      if ($sidebar_responsive.length != 0) {
        $sidebar_responsive.css('background-image', 'url("' + new_image + '")');
      }
    });

    $('.switch-sidebar-image input').change(function() {
      $full_page_background = $('.full-page-background');

      $input = $(this);

      if ($input.is(':checked')) {
        if ($sidebar_img_container.length != 0) {
          $sidebar_img_container.fadeIn('fast');
          $sidebar.attr('data-image', '#');
        }

        if ($full_page_background.length != 0) {
          $full_page_background.fadeIn('fast');
          $full_page.attr('data-image', '#');
        }

        background_image = true;
      } else {
        if ($sidebar_img_container.length != 0) {
          $sidebar.removeAttr('data-image');
          $sidebar_img_container.fadeOut('fast');
        }

        if ($full_page_background.length != 0) {
          $full_page.removeAttr('data-image', '#');
          $full_page_background.fadeOut('fast');
        }

        background_image = false;
      }
    });

    $('.switch-sidebar-mini input').change(function() {
      $body = $('body');

      $input = $(this);

      if (md.misc.sidebar_mini_active == true) {
        $('body').removeClass('sidebar-mini');
        md.misc.sidebar_mini_active = false;

        $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar();

      } else {

        $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar('destroy');

        setTimeout(function() {
          $('body').addClass('sidebar-mini');

          md.misc.sidebar_mini_active = true;
        }, 300);
      }

      // we simulate the window Resize so the charts will get updated in realtime.
      var simulateWindowResize = setInterval(function() {
        window.dispatchEvent(new Event('resize'));
      }, 180);

      // we stop the simulation of Window Resize after the animations are completed
      setTimeout(function() {
        clearInterval(simulateWindowResize);
      }, 1000);
    });
  });
});

// =====================================================
// AjAX script
// load dynamic content on webpage without reloading it
// =====================================================
//executed after the page has loaded
$(document).ready(function(){

  sections = $('.sidebar-wrapper ul.nav li a p').map(function(){ return $(this); }).get();
  //check if the URL has a reference to a page and load it
  checkURL();
  //traverse through all our navigation links..
  $('.sidebar-wrapper ul.nav li a').click(function (e){
    //.. and assign them a new onclick event, using their own hash as a parameter (#page1 for example)
    checkURL(this.hash);
  });
  //check for a change in the URL every 250 ms to detect if the history buttons have been used
  setInterval(checkURL,250);
});

//here we store the current URL hash
var lastUrl;

function checkURL(hash)
{
  if(!hash)
  {
    //if no parameter is provided, use the hash value from the current address
    hash = window.location.hash;
  }
  // if the hash value has changed
  if(hash != lastUrl)
  {
    //update the current hash
    lastUrl = hash;
    updateActive(hash);
    // and load the new page
    loadPage(hash);
  }
}

//the function that loads pages via AJAX
function loadPage(url)
{
  //strip the #page part of the hash and leave only the page number
  url = url.replace('#','');
  //show the rotating gif animation
  $('div.spinner-grow').css('visibility','visible');

  //create an ajax request to load_page.php
  $.ajax({
    type: "GET",
    url: "../config/load_page.php",
    //with the Section as a parameter
    data: 'section='+url,
    //expect html to be returned
    dataType: "html",
    success: function(msg){
      //if no errors
      if(parseInt(msg)!=0)
      {
        //load the returned html into pageContent
        $('div.content').html(msg);                               
        //and hide the rotating gif
        $('div.spinner-grow').css('visibility','hidden');            
      }
      else
      {
        //Return error into pageContent
        $('div.content').html("Err! Page Doesn't exists.");
        //and hide the rotating gif       
        $('div.spinner-grow').css('visibility','hidden');
      }
    }
  });
}

function updateActive(sectionUrl)
{
  sectionUrl = sectionUrl.replace('#','');
  if(sectionUrl == '')
  {
    sectionUrl = 'dashboard';
  }
  
  for(var i = 0;i < sections.length;i++)
  {
    if(sectionUrl == sections[i].text().toLowerCase())
    {
      sections[i].parents('.nav-item').addClass('active');
      continue;
    }
    sections[i].parents('.nav-item').removeClass('active');
  }
}


// Profile Section
// Hide change image button
$('.card-avatar').mouseenter(function(){
  $(this).children('div').removeClass('d-none');
});