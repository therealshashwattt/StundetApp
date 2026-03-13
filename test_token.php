
<!DOCTYPE html>
<html>
<head>
  <title>FCM Token Test</title>
</head>
<body>
<h2>Firebase Token Generator</h2>
<button onclick="getToken()">Generate Token</button>
<p id="token"></p>

<script type="module">
    if ('serviceWorker' in navigator) {
  navigator.serviceWorker.register('/StudentAppDatalaysSoftware/firebase-messaging-sw.js')
    .then(reg => console.log('SW registered with scope:', reg.scope))
    .catch(err => console.error('SW registration failed:', err));
}

    
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
import { getMessaging, getToken, onMessage } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging.js";

const firebaseConfig = {
  apiKey: "AIzaSyCKDVLLW20WPWxKlRXLgnBk0P-6kM87P_s",
  authDomain: "studentappdatalayssoftware.firebaseapp.com",
  projectId: "studentappdatalayssoftware",
  storageBucket: "studentappdatalayssoftware.firebasestorage.app",
  messagingSenderId: "239801368091",
  appId: "1:239801368091:web:e9a190ed486d8d0c0a42be",
};

const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);

window.getToken = async () => {
  const token = await getToken(messaging, {
    vapidKey: "BD-JZEBjnv-4y0Zy0O98KT-EoaEMBiw5FT0F8H4PrLQySaxPAX3EY3uBgc7SEeuDCspREHjWnNAfnfwvMwhATxw"
  });

  document.getElementById("token").innerText = token;
  console.log("TOKEN:", token);
};
onMessage(messaging, (payload) => {
  console.log("Message received:", payload);

  navigator.serviceWorker.getRegistration().then(registration => {
    if (!registration) {
      console.error("No service worker registered.");
      return;
    }

    registration.showNotification(
      payload.notification.title,
      {
        body: payload.notification.body,
        icon: "https://datalays.com/favicon.ico",
        badge: "https://datalays.com/favicon.ico"
      }
    );
  });
});

</script>

</body>
</html>
