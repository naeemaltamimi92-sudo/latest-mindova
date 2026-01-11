# Email Configuration Instructions

## ✅ Best Configuration Setup

**How it works**:
- Emails are **SENT FROM**: naeem.altamimi92@gmail.com (using Gmail SMTP)
- Emails are **DELIVERED TO**: n3eem-altamimi@hotmail.com
- Gmail's SMTP is more reliable for application emails

---

## 🔑 Required: Generate Gmail App Password

### Step-by-Step Instructions:

### 1️⃣ Enable 2-Step Verification (if not already enabled)

1. Go to: https://myaccount.google.com/security
2. Find **"2-Step Verification"** section
3. Click **"Turn on"** if not enabled
4. Follow the prompts to set it up (usually requires phone verification)

### 2️⃣ Generate App Password

1. Go to: https://myaccount.google.com/apppasswords
   - **OR** Google Account → Security → 2-Step Verification → App passwords

2. You may need to sign in again

3. Click **"Select app"** → Choose **"Mail"**

4. Click **"Select device"** → Choose **"Other (Custom name)"**
   - Type: **"Mindova Platform"**

5. Click **"Generate"**

6. Google will show a **16-character password** like:
   ```
   abcd efgh ijkl mnop
   ```

7. **Copy this password** (without spaces)

### 3️⃣ Update .env File

1. Open: `C:\xampp\htdocs\mindova\.env`

2. Find this line:
   ```
   MAIL_PASSWORD=YOUR_GMAIL_APP_PASSWORD_HERE
   ```

3. Replace with your App Password (remove spaces):
   ```
   MAIL_PASSWORD=abcdefghijklmnop
   ```

4. Save the file

### 4️⃣ Test Email Sending

Run this command:
```bash
php test_bug_email_sync.php
```

You should receive the email in your Hotmail inbox: **n3eem-altamimi@hotmail.com**

---

## ⚠️ Important Security Notes

1. **Never share your App Password**
   - It gives full access to send emails from your account
   - Keep it secret like your regular password

2. **App Password vs Regular Password**
   - ❌ Don't use your regular Gmail password
   - ✅ Always use the App Password for applications

3. **Revoke if compromised**
   - You can revoke App Passwords anytime at: https://myaccount.google.com/apppasswords
   - Generate a new one if needed

---

## 📧 Current Configuration Summary

```
Sending Method: Gmail SMTP
SMTP Server: smtp.gmail.com
Port: 587
Encryption: TLS
Username: naeem.altamimi92@gmail.com
From Address: naeem.altamimi92@gmail.com
To Address: n3eem-altamimi@hotmail.com
```

---

## 🔍 Troubleshooting

### Error: "Invalid credentials"
- Make sure you're using the **App Password**, not your regular password
- Remove any spaces from the App Password
- Make sure 2-Step Verification is enabled

### Error: "Connection timeout"
- Check your internet connection
- Make sure port 587 is not blocked by firewall

### Not receiving emails
- Check your Hotmail spam/junk folder
- Wait a few minutes (sometimes delayed)
- Verify the recipient email: n3eem-altamimi@hotmail.com

### Still not working?
- Run: `php test_bug_email_sync.php` to see detailed error messages

---

## ✅ What to Do Next

1. ✅ Enable 2-Step Verification on Gmail (if not enabled)
2. ✅ Generate App Password from Google Account
3. ✅ Update `.env` with the App Password
4. ✅ Run test: `php test_bug_email_sync.php`
5. ✅ Check your Hotmail inbox

---

**Estimated Time**: 5-10 minutes

Once completed, all bug reports will be automatically sent to **n3eem-altamimi@hotmail.com**! 🎉
