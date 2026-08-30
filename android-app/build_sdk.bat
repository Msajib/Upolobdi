@echo off
set "JAVA_HOME=C:\Users\Elitebook\jdk-17.0.10+7"
set "ANDROID_HOME=C:\Users\Elitebook\android-sdk"
set "ANDROID_SDK_ROOT=C:\Users\Elitebook\android-sdk"
set "PATH=%JAVA_HOME%\bin;%ANDROID_HOME%\cmdline-tools\latest\bin;%PATH%"

echo =========================================================
echo    [1/3] Installing SDK 34 and Build-Tools...
echo =========================================================
call "%ANDROID_HOME%\cmdline-tools\latest\bin\sdkmanager.bat" --sdk_root="%ANDROID_HOME%" "platforms;android-34" "build-tools;34.0.0"

echo =========================================================
echo    [2/3] Building APK with Gradle...
echo =========================================================
cd /d "C:\Users\Elitebook\Desktop\Projects\Upolobdi\android-app"
call gradlew.bat assembleDebug --no-daemon

echo =========================================================
echo    [3/3] Copying APK to Website Public Downloads...
echo =========================================================
if not exist "..\public\downloads" mkdir "..\public\downloads"
copy /y "app\build\outputs\apk\debug\app-debug.apk" "..\public\downloads\upolobdi.apk"
copy /y "app\build\outputs\apk\debug\app-debug.apk" "..\public\upolobdi.apk"

echo =========================================================
echo    APK BUILD COMPLETED SUCCESSFULLY!
echo =========================================================
