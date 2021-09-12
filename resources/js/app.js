require('./bootstrap');

require('alpinejs');


window.Echo.private('orders')
    .listen('.order.create',function(even){
        alert(`new oreder create #${even.order.number}`);
    });// if has new(custom) name write it and first add dot . , if dont have write name Class

    window.Echo.private(`Notification.${userId}`)
            .notification(function(e){

                console.log(e);

                var count = Number($('#unread').text());
                count++;
                $('.unread').text(count);

                $('#notifications').prepend(`
                        <a href="#"
                            class="dropdown-item">
                            <i class="fas fa-box"></i>
                                ${ e.title }
                                <span class="badge badge-danger">*</span>

                            <span class="float-right text-muted text-sm">
                                ${ e.time }
                            </span>
                        </a>
                    <div class="dropdown-divider"></div>
                `);

                alert(e.title); // notify message
            });

    const chat = window.Echo.join('chat')
    .here((users) => {
        for (i in users) {
            $('#users').append(`<li id="user-${users[i].id}">${users[i].name}</li>`);
        }
    })
    .joining((user) => {
        $('#messages').append(`<div class="shadow-sm my-5 sm:rounded-lg">
            User ${user.name} joined!
        </div>`);
        $('#users').append(`<li id="user-${user.id}">${user.name}</li>`);
    })
    .leaving((user) => {
        $('#messages').append(`<div class="shadow-sm my-5 sm:rounded-lg">
            User ${user.name} left!
        </div>`);
        $('#users').find(`#user-${user.id}`).remove();
    })
    .listen('MessageSent', (event) => {
        addMessage(event);
    })
    .listenForWhisper('typing-start', (e) => {
        /*$('#messages').append(`<div class="shadow-sm my-5 sm:rounded-lg">
            ${e.name} is typing...
        </div>`);*/
        $('#typing').css('display', 'block');
    })
    .listenForWhisper('typing-stop', (e) => {
        $('#typing').css('display', 'none');
    });

(function($) {
    $('#chat-form').on('submit', function(event) {
        event.preventDefault();
        $.post($(this).attr('action'), $(this).serialize(), function(res) {
            $('#chat-form input').val('');
        })
    });

    // $('#chat-form input').on('keypress', function(e) {
    //     if (e.key == 'Enter') return;
    //     typingTime = new Date();
    //     if (!typing) {
    //         typing = true;
    //         chat.whisper('typing-start', {
    //             name: 'Someone'
    //         });
    //         typingInterval = setInterval(function() {
    //             var seconds = ((new Date) - typingTime);
    //             if (seconds > 600) {
    //                 typing = false;
    //                 clearInterval(typingInterval);
    //                 chat.whisper('typing-stop', {
    //                     name: 'Someone'
    //                 });
    //             }
    //         }, 1000);
    //     }
    // })

})(jQuery);

// function addMessage(event) {
//     $('#messages').append(`<div class="shadow-sm my-5 sm:rounded-lg">
//         ${event.sender.name}: ${event.message}
//     </div>`);
// }


function addMessage(event){
    $("#messages").append(`

        <div class="direct-chat-msg">
            <div class="direct-chat-infos clearfix">
                <span class="direct-chat-name float-left">${event.sender.name}</span>
                <span class="direct-chat-timestamp float-right">23 Jan 2:00 pm</span>
            </div>

            <!-- /.direct-chat-infos -->
                <img class="direct-chat-img" src="${event.sender.profile.image_url}" alt="message user image">
            <!-- /.direct-chat-img -->

            <div class="direct-chat-text">
                ${event.message}
            </div>
        </div>
    `);
}
