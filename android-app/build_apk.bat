@echo off
echo ===================================================
echo     Upolobdi Android App - 1-Click APK Builder
echo ===================================================
echo.

if exist "C:\Program Files\Android\Android Studio" (
    echo [INFO] Android Studio detected on your system.
)

echo [1] Checking Java JDK...
where javac >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    echo [NOTICE] Java JDK is not in your system PATH.
    echo Please install Android Studio or OpenJDK 17 to build locally via command line,
    echo OR open this 'android-app' folder in Android Studio and click 'Build -> Build APK'.
    echo.
    pause
    exit /b
)

echo [2] Building Release APK with Gradle...
call gradlew.bat assembleRelease

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ===================================================
    echo  SUCCESS! APK generated successfully:
    echo  Location: app\build\outputs\apk\release\app-release-unsigned.apk
    echo ===================================================
) else (
    echo.
    echo [ERROR] Build failed. Please open the android-app folder in Android Studio.
)

pause
