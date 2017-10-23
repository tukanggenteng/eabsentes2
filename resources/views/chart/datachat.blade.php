@foreach($chats as $chat)
    @if (Auth::user()->id==$chat->user_id)
        <!-- Message to the right -->
        <div class="direct-chat-msg right">
            <div class="direct-chat-info clearfix">
                <span class="direct-chat-name pull-right">{{$chat->name}}</span>
                <span class="direct-chat-timestamp pull-left">{{$chat->created_at}}</span>
            </div>
            <!-- /.direct-chat-info -->
            <img class="direct-chat-img" src="dist/img/user3-128x128.jpg" alt="message user image">
            <!-- /.direct-chat-img -->
            <div class="direct-chat-text">
                {{$chat->text}}
            </div>
            <!-- /.direct-chat-text -->
        </div>
        @else
        <div class="direct-chat-msg" >
            <div class="direct-chat-info clearfix">
                <span class="direct-chat-name pull-left">{{$chat->name}}</span>
                <span class="direct-chat-timestamp pull-right">{{$chat->created_at}}</span>
            </div>
            <!-- /.direct-chat-info -->
            <img class="direct-chat-img" src="dist/img/user1-128x128.jpg" alt="message user image">
            <!-- /.direct-chat-img -->
            <div class="direct-chat-text">
                {{$chat->text}}
            </div>
            <!-- /.direct-chat-text -->
        </div>
        <!-- /.direct-chat-msg -->
        @endif


@endforeach