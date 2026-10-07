package org.upolobdi.app;

/**
 * =========================================================================
 * UPOLOBDI APP CONFIGURATION
 * =========================================================================
 * You can change your website or API domain URL right here!
 *
 * Examples:
 * - Production: "https://www.upolobdi.com"
 * - Ngrok Tunnel: "https://your-tunnel.ngrok-free.app"
 * - Local Wi-Fi:  "http://192.168.0.105:8000"
 */
public final class AppConfig {

    // >>>>> CHANGE YOUR MAIN API & WEBSITE URL HERE <<<<<
    public static final String BASE_URL = "https://www.upolobdi.com";

    // Only these hosts may render inside the app; anything else opens externally
    public static final String[] ALLOWED_HOSTS = { "www.upolobdi.com", "upolobdi.com" };

    // Application Version Name
    public static final String APP_VERSION = "1.0.1";

    // Prevent instantiation
    private AppConfig() {}
}
