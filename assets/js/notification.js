// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries
// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
    apiKey: "AIzaSyA8fXYZre4nOBnWvV6tYttjR_laBzFSJE4",
    authDomain: "nativebit-175ba.firebaseapp.com",
    projectId: "nativebit-175ba",
    storageBucket: "nativebit-175ba.appspot.com",
    messagingSenderId: "695494499148",
    appId: "1:695494499148:web:0be6573f7b88f1f67a0de9",
    measurementId: "G-GERRDB3419"
};

firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();
messaging.usePublicVapidKey("BMbDoUsYZnTpaZ5OnBWc-NlwsPk5wxBKXkKgAi8EnvQ2AtJpvap6OsW83n01g_kej2j1K8-cU0nEPhVLmrvl76Y");

// Get Instance ID token. Initially this makes a network call, once retrieved
// subsequent calls to getToken will return from cache.
messaging.getToken({ vapidKey: 'BMbDoUsYZnTpaZ5OnBWc-NlwsPk5wxBKXkKgAi8EnvQ2AtJpvap6OsW83n01g_kej2j1K8-cU0nEPhVLmrvl76Y' }).then((currentToken) => {
    if (currentToken) {
        $("#loginform #web_push_token").val(currentToken);
    } else {
        // Show permission request.
        console.log('No Instance ID token available. Request permission to generate one.');
    }
}).catch((err) => {
    console.log('An error occurred while retrieving token. ', err);
});

// Handle incoming messages. Called when:
// - a message is received while the app has focus
// - the user clicks on an app notification created by a service worker
//   `messaging.setBackgroundMessageHandler` handler.
messaging.onMessage((payload) => {
    payload = JSON.parse(payload.data.data);
    var notificationTitle = payload.title;
    var notificationOptions = {
        body: payload.message,
        icon : payload.image,
        tag : payload.onclick,
    };

    const notification = new Notification(notificationTitle, notificationOptions);

    // Handle the click event
    notification.onclick = function () {
        // Check if the window is already open
        var isOpen = false;

        // Iterate through existing windows
        $.each(window.frames, function (index, frame) {
            if (frame.location.href === payload.onclick) {
                isOpen = true;
                // Focus on the existing window
                frame.focus();
                return false; // Exit the loop
            }
        });

        // If the window is not open, open a new one
        if (!isOpen) {
            window.open(payload.onclick, '_blank');
        }
    };
});

