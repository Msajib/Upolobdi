# 📱 Upolobdi Somiti - Android Application

This directory (`android-app/`) contains the complete native Android project source code for **Upolobdi Somiti (উপলব্ধি সমবায় সমিতি)**.

---

## ✨ Features Included

- **Native WebView Performance:** Hardware accelerated rendering with custom user agent and session cookies.
- **Camera & Gallery File Uploads:** Full support for taking photos and uploading receipts and avatars directly inside the app (`WebChromeClient.onShowFileChooser`).
- **Pull-To-Refresh:** Native `SwipeRefreshLayout` with theme-tailored gold/emerald spinner.
- **Offline Error Handling:** Custom offline screen with retry button when no internet is available.
- **Status Bar & Splash Screen:** Deep royal navy branding (`#020617`) and gold accents.
- **Direct Phone / WhatsApp & PDF Handling:** Direct integration with dialer and PDF receipts.

---

## 🛠️ How to Generate the APK File to Share with Friends

### Method 1: Using Android Studio (Recommended & Easiest)

1. Download and install [Android Studio](https://developer.android.com/studio) (if not already installed).
2. Open Android Studio and click **Open** ➔ Select the `android-app` folder inside your project (`c:\Users\Elitebook\Desktop\Projects\Upolobdi\android-app`).
3. Allow Gradle to sync dependencies (takes 1-2 minutes on first run).
4. In the top menu, go to:
   - **Build** ➔ **Build Bundle(s) / APK(s)** ➔ **Build APK(s)**.
5. Once complete, click **locate** in the popup notification at the bottom right.
6. Your APK file will be ready at:
   ```
   android-app/app/build/outputs/apk/debug/app-debug.apk
   ```
7. **Share this `.apk` file directly** via WhatsApp, Google Drive, or Telegram with your friends!

---

### Method 2: 1-Click PWA (No Build Tools Required)

Since the website is fully mobile responsive, users can also install it as an instant standalone app from their Android phone:
1. Open Chrome on Android and visit `https://your-domain.com`.
2. Tap the **3 dots menu (⋮)** at the top right.
3. Tap **"Install App"** (or **"Add to Home screen"**).
4. An app icon with full-screen native experience will appear on their phone's home screen!

---

### 🌐 Changing the App URL

To point the app to your live domain or custom server:
Open `android-app/app/src/main/res/values/strings.xml` and update:
```xml
<string name="web_url">https://yourdomain.com</string>
```
Or in `MainActivity.java`:
```java
private static final String TARGET_URL = "https://yourdomain.com";
```
