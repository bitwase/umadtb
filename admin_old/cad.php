<script>
  var OneSignal = window.OneSignal || [];
  OneSignal.push(function() {
    OneSignal.init({
      appId: "f6eb233f-6433-4b68-9dec-987d419be263",
      notifyButton: {
        enable: true,
      },
    });
  });
</script>

<body>
Aguarde 10 segundos...<br><br>
  <a href="#" id="subscribe-link" style="display: none;">Receber Notificações</a>
  <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async="async"></script>
  <script>
    function subscribe() {
      OneSignal.push(["registerForPushNotifications"]);
      event.preventDefault();
    }

    var OneSignal = OneSignal || [];
    /* This example assumes you've already initialized OneSignal */
    OneSignal.push(function() {
      // If we're on an unsupported browser, do nothing
      if (!OneSignal.isPushNotificationsSupported()) {
        return;
      }
      OneSignal.isPushNotificationsEnabled(function(isEnabled) {
        if (isEnabled) {
          // The user is subscribed to notifications
          // Don't show anything
        } else {
          document.getElementById("subscribe-link").addEventListener('click', subscribe);
          document.getElementById("subscribe-link").style.display = '';
        }
      });
    });
  </script>
</body>