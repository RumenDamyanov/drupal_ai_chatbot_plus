// Placeholder for chatbot JS logic
(function ($, Drupal) {
  Drupal.behaviors.aiChatbot = {
    attach: function (context, settings) {
      // Accessibility: focus input on open
      var $widget = $('.ai-chatbot-widget', context).once('ai-chatbot-init');
      $widget.each(function () {
        var $this = $(this);
        // Add input if not present
        if ($this.find('.ai-chatbot-input input[type="text"]').length === 0) {
          $this.find('.ai-chatbot-input').html('<input type="text" aria-label="Chat message" placeholder="Type your message..." autocomplete="off" /><button type="button" aria-label="Send">Send</button>');
        }
        // Focus input on widget open
        $this.find('input[type="text"]').focus();
        // Handle send
        $this.find('button').on('click', function () {
          var msg = $this.find('input[type="text"]').val();
          if (msg) {
            var $messages = $this.find('.ai-chatbot-messages');
            $messages.append('<div class="ai-chatbot-message ai-chatbot-user">' + $('<div>').text(msg).html() + '</div>');
            $this.find('input[type="text"]').val('').focus();
            // TODO: AJAX call to backend for response
          }
        });
        // Enter key submits
        $this.find('input[type="text"]').on('keydown', function (e) {
          if (e.key === 'Enter') {
            $this.find('button').click();
          }
        });
      });
      // Modal logic
      $('.ai-chatbot-modal', context).once('ai-chatbot-modal').each(function () {
        var $modal = $(this);
        $modal.attr('role', 'dialog').attr('aria-modal', 'true');
        // Close on background click
        $modal.on('click', function (e) {
          if (e.target === this) {
            $modal.hide();
          }
        });
      });
    }
  };
})(jQuery, Drupal);
