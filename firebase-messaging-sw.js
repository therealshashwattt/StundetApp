importScripts('https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging-compat.js');

firebase.initializeApp({
     apiKey: "AIzaSyCKDVLLW20WPWxKlRXLgnBk0P-6kM87P_s",
  authDomain: "studentappdatalayssoftware.firebaseapp.com",
  projectId: "studentappdatalayssoftware",
  storageBucket: "studentappdatalayssoftware.firebasestorage.app",
  messagingSenderId: "239801368091",
  appId: "1:239801368091:web:e9a190ed486d8d0c0a42be",
});

const messaging = firebase.messaging();

messaging.onBackgroundMessage(function(payload) {
  console.log('[SW] Message received:', payload);

  const title = payload.notification.title;
  const options = {
    body: payload.notification.body,
  };

  self.registration.showNotification(title, options);
});