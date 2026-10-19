package org.upolobdi.app;

import android.content.Context;
import android.webkit.JavascriptInterface;
import android.widget.Toast;

public class WebAppInterface {
    Context mContext;

    WebAppInterface(Context c) {
        mContext = c;
    }

    @JavascriptInterface
    public void showToast(String toast) {
        Toast.makeText(mContext, toast, Toast.LENGTH_SHORT).show();
    }

    @JavascriptInterface
    public String getAppVersion() {
        return AppConfig.APP_VERSION;
    }

    @JavascriptInterface
    public boolean isNativeApp() {
        return true;
    }

    @JavascriptInterface
    public void printPage() {
        if (mContext instanceof MainActivity) {
            ((MainActivity) mContext).runOnUiThread(() -> ((MainActivity) mContext).printCurrentPage());
        }
    }
}
