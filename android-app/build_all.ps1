$env:JAVA_HOME = "C:\Users\Elitebook\jdk-17.0.10+7"
$env:ANDROID_HOME = "C:\Users\Elitebook\android-sdk"
$env:ANDROID_SDK_ROOT = "C:\Users\Elitebook\android-sdk"
$env:PATH = "$env:JAVA_HOME\bin;" + "$env:ANDROID_HOME\cmdline-tools\latest\bin;" + "$env:PATH"

Write-Host "========================================================="
Write-Host " [1/2] Compiling Native Android APK with Gradle..."
Write-Host "========================================================="

Set-Location "C:\Users\Elitebook\Desktop\Projects\Upolobdi\android-app"
& ".\gradlew.bat" assembleDebug --no-daemon --stacktrace

Write-Host "========================================================="
Write-Host " [2/2] Copying APK to Website Download Directory..."
Write-Host "========================================================="

$outputApk = "app\build\outputs\apk\debug\app-debug.apk"
if (Test-Path $outputApk) {
    if (!(Test-Path "..\public\downloads")) {
        New-Item -ItemType Directory -Path "..\public\downloads" -Force
    }
    Copy-Item $outputApk "..\public\downloads\upolobdi.apk" -Force
    Copy-Item $outputApk "..\public\upolobdi.apk" -Force
    Write-Host "SUCCESS! APK is ready at public/downloads/upolobdi.apk"
    Get-Item "..\public\downloads\upolobdi.apk" | Select-Object Name, Length, LastWriteTime
} else {
    Write-Host "ERROR: APK build output not found."
}
