function facebook_main() {
  FB.Event.subscribe('comment.create',
    function(response) {
      $.get('comment_notify');
    }
  );
}

