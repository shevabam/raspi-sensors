/** 
 * jQuery plugin for simple notifications
 * 
 * @author ShevAbam <shevarezo.fr>
 * 
 * [ Usage ]
 * 
    <script>
    function run(type)
    {
        $('body').gNotifier2({
            'title'     : 'Hello World!',
            'text'      : 'Lorem ipsum in dolor sit amet',
            'type'      : type,
            'animation' : true,
            'position'  : 'top-right'
        });
    }
    </script>
    
    <a href="javascript:;" onclick="run('error');">Click here !</a>
 * 
 */

(function($){

    $.fn.gNotifier2 = function(options){
        
        // Settings
        var defaults = {
            title           : '',           // Notification title
            text            : '',           // Notification text
            type            : 'success',    // Notification type (success (default) ; notice ; warning ; error)
            force_width     : false,        // Foce notification width
            width           : '600px',      // Notification width (can be changed in CSS)
            position        : 'top',        // Notification position (top ; top-left ; top-right ; bottom ; bottom-left ; bottom-right)
            animation       : false,        // Enable or not opening / closing animations (needs animate.css)
            timeout         : 3000,         // Time in ms before closing (default : 3s)
            onClose         : ''            // Callback after closing notification
        }
        
        var settings = $.extend(defaults, options);

        return this.each(function(){

            var $this = $(this);
            
            if ($('.gnotifier_notifications').length == 0)
                $this.prepend('<div class="gnotifier_notifications"/>');

            var $notifs = $('.gnotifier_notifications');
            

            // Notification markup
            var type = '';
            if (settings.type != '')
                type = ' '+settings.type;

            var html = '';
            html += '<div class="gnotifier_notif'+type+'">';
            html +=     '<h2>'+settings.title+'</h2>';
            html +=     '<p>'+settings.text+'</p>';
            html += '</div>';

            $notifs.append(html);

            var $notif = $('.gnotifier_notifications > .gnotifier_notif');


            // Animation
            var animation_show = '';
            var animation_hide = '';

            // Force notification width
            if (settings.force_width && settings.width != '')
                $notifs.css('width', settings.width);

            // Calculation of the screen middle
            var pos_left = ($this.width() / 2) - ($notifs.width() / 2);


            // bottom | bottom-left | bottom-right
            var regexPosition_bottom = new RegExp(/^bottom(((-left)|(-right))?)$/);

            // top | top-left | top-right
            var regexPosition_top    = new RegExp(/^top(((-left)|(-right))?)$/);

            // Notification positionning
            if (regexPosition_bottom.test(settings.position)) // bottom | bottom-left | bottom-right
            {
                $notifs.css('bottom', '0');
                
                if (settings.position == 'bottom-left')
                {
                    $notifs.css('left', '0');
                    animation_show = 'bounceInRight';
                    animation_hide = 'bounceOutLeft';
                }
                else if (settings.position == 'bottom-right')
                {
                    $notifs.css('right', '0');
                    animation_show = 'bounceInLeft';
                    animation_hide = 'bounceOutRight';
                }
                else
                {
                    $notifs.css('left', pos_left+'px');
                    animation_show = 'bounceInUp';
                    animation_hide = 'bounceOutDown';
                }
            }
            else if (regexPosition_top.test(settings.position)) // top | top-left | top-right
            {
                $notifs.css('top', '0');
                
                if (settings.position == 'top-left')
                {
                    $notifs.css('left', '0');
                    animation_show = 'bounceInRight';
                    animation_hide = 'bounceOutLeft';
                }
                else if (settings.position == 'top-right')
                {
                    $notifs.css('right', '0');
                    animation_show = 'bounceInLeft';
                    animation_hide = 'bounceOutRight';
                }
                else
                {
                    $notifs.css('left', pos_left+'px');
                    animation_show = 'bounceInDown';
                    animation_hide = 'bounceOutUp';
                }
            }


            // Animate opening
            if (settings.animation)
                $notif.addClass('animated '+animation_show);


            // Notification is closed after ... milliseconds (onClose option)
            if (settings.timeout != '0')
            {
                setTimeout(function(){
                    $notif.trigger('click');
                }, settings.timeout);
            }


            // Closing the notification after clicking on it
            $notif.on('click', function(e){
                e.preventDefault();

                // Animate closing
                if (settings.animation)
                {
                    $(this).addClass('animated '+animation_hide).delay(500).slideUp(300, function(){
                        if ($(this).siblings().length == 0)
                            $('.gnotifier_notifications').remove();

                        $(this).remove();
                    });
                }
                else
                {
                    if ($notifs.children().length == 0)
                        $('.gnotifier_notifications').remove();

                    $(this).remove();
                }

                if (settings.onClose != '')
                    settings.onClose.call(this);
            });

        });
    }

})(jQuery);