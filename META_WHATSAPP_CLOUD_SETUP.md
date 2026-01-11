# Meta WhatsApp Cloud API - Complete Setup Guide

**Goal**: Send WhatsApp messages WITHOUT Twilio, using Meta's direct API

**Benefits**:
- ✅ **FREE** (1,000 conversations/month)
- ✅ Recipients DON'T need to join anything
- ✅ No Twilio fees
- ✅ Direct integration with Meta/Facebook

---

## 📋 Step-by-Step Setup

### Step 1: Create Facebook Developer Account (5 minutes)

1. **Go to**: https://developers.facebook.com/

2. **Click**: "Get Started" (top right)

3. **Login** with Facebook or create new account

4. **Complete registration**:
   - Enter your name
   - Email: haltamimi468@gmail.com
   - Accept terms

5. **Verify email** if prompted

---

### Step 2: Create a Business App (5 minutes)

1. After login, click **"My Apps"** in top menu

2. Click **"Create App"** button

3. Select **"Business"** as app type

4. Click **"Next"**

5. Fill out app details:
   ```
   App Name: Mindova Notifications
   App Contact Email: haltamimi468@gmail.com
   Business Account: [Select or create new]
   ```

6. Click **"Create App"**

7. **Complete security check** (if prompted)

---

### Step 3: Add WhatsApp Product (2 minutes)

1. In your app dashboard, scroll down to **"Add products to your app"**

2. Find **"WhatsApp"** card

3. Click **"Set up"**

4. You'll be redirected to WhatsApp Business Platform setup

---

### Step 4: Get Test Credentials (Instant!)

You'll now see the **"Quickstart"** page with:

#### A. Test Phone Number (Already Provided!)

Meta gives you a test number immediately:
```
From: +1 555 025 3483 (example - yours will be different)
```

✅ You can send messages RIGHT NOW with this number!

#### B. Temporary Access Token

1. Look for **"Temporary access token"** section

2. Click **"Copy"** button

3. **IMPORTANT**: This token expires in 24 hours
   - Later we'll generate a permanent token
   - For now, this is fine for testing

#### C. Phone Number ID

1. Look for **"Phone number ID"**

2. Copy this number (format: 123456789012345)

#### D. WhatsApp Business Account ID

1. Look for **"WhatsApp Business Account ID"**

2. Copy this number

---

### Step 5: Configure Your App (2 minutes)

1. **Open** `C:\xampp\htdocs\mindova\.env`

2. **Add** these lines at the end:

```env
# Meta WhatsApp Cloud API (NO TWILIO!)
META_WHATSAPP_CLOUD_ENABLED=true
META_WHATSAPP_ACCESS_TOKEN=EAAxxxxxxxxxxxxxxxxxx  # Paste your temporary token
META_WHATSAPP_PHONE_NUMBER_ID=123456789012345    # Paste your Phone Number ID
META_WHATSAPP_BUSINESS_ACCOUNT_ID=123456789      # Paste your Business Account ID
```

3. **Save** the file

---

### Step 6: Add Test Recipient (Important!)

With test number, you can ONLY send to specific phone numbers you add to the allowed list:

1. In Meta dashboard, look for **"To"** section

2. Click **"Add phone number"**

3. Enter your phone number: **+962792903538**

4. Click **"Verify"**

5. You'll receive a verification code via WhatsApp

6. Enter the code

7. ✅ Now this number can receive messages!

---

### Step 7: Test It! (1 minute)

Run this command:

```bash
php test_meta_whatsapp.php
```

You should see:
```
✅ MESSAGE SENT SUCCESSFULLY!
📱 Check your WhatsApp!
✅ You are now sending WhatsApp messages WITHOUT Twilio!
```

**Check your phone** - you should receive a WhatsApp message! 🎉

---

### Step 8: Make It Production Ready (Optional)

The test number works, but for production you need to:

#### A. Get Permanent Access Token

1. In Meta dashboard, go to **"System Users"**

2. Create a system user

3. Generate permanent token (doesn't expire)

4. Update `.env` with permanent token

#### B. Apply for Production Access

1. In WhatsApp settings, click **"Get started"** under production

2. Fill out business profile:
   ```
   Business Name: Mindova
   Business Website: [Your website]
   Business Description: AI-powered innovation platform
   ```

3. Submit for review (24-48 hours)

#### C. Get Your Own Phone Number

After approval:

1. Go to **"Phone Numbers"** section

2. Click **"Add phone number"**

3. Choose option:
   - Use existing business phone
   - Get new number from Meta

4. Complete verification

5. Update `.env` with production phone number ID

---

## 🎯 Quick Configuration Reference

### For Testing (Works NOW):
```env
META_WHATSAPP_CLOUD_ENABLED=true
META_WHATSAPP_ACCESS_TOKEN=EAAxxxxxx  # 24-hour token from quickstart
META_WHATSAPP_PHONE_NUMBER_ID=123456  # Test number ID
META_WHATSAPP_BUSINESS_ACCOUNT_ID=123 # Your business account
```

### For Production (After Approval):
```env
META_WHATSAPP_CLOUD_ENABLED=true
META_WHATSAPP_ACCESS_TOKEN=EAAxxxxxx  # Permanent system user token
META_WHATSAPP_PHONE_NUMBER_ID=123456  # Your production number ID
META_WHATSAPP_BUSINESS_ACCOUNT_ID=123 # Your business account
```

---

## 📨 How to Send Messages

### Option 1: Use Unified Service (Recommended)

```php
use App\Services\NotificationChannelService;

$user = User::find(1);

NotificationChannelService::send(
    user: $user,
    templateName: 'task_assigned',
    variables: [
        'task_title' => 'Build amazing feature',
        'link' => 'http://localhost:8000/tasks/1'
    ],
    preferredChannel: 'whatsapp_cloud'  // Force Meta WhatsApp
);
```

### Option 2: Direct Meta Service

```php
use App\Services\WhatsAppCloudService;

$service = new WhatsAppCloudService();
$messageId = $service->sendMessage($user, 'task_assigned', [
    'task_title' => 'Build amazing feature',
    'link' => 'http://localhost:8000/tasks/1'
]);
```

---

## 💰 Cost Comparison

| Messages/Month | Twilio | Meta Cloud |
|----------------|--------|------------|
| 0-1,000 | $5-10 | **FREE** ✅ |
| 1,001-5,000 | $25-50 | $5-10 |
| 5,001-10,000 | $50-100 | $10-20 |

**Meta is much cheaper!** 🎉

---

## ⚠️ Important Notes

### Test Number Limitations

- ✅ Can send to verified numbers only
- ✅ 100 messages per day limit
- ✅ Perfect for testing
- ❌ NOT for production

### Production Number (After Approval)

- ✅ Send to ANY WhatsApp number
- ✅ Unlimited messages (pay as you go)
- ✅ Professional business profile
- ✅ No recipient joining required! ✅

---

## 🔧 Troubleshooting

### Error: "Recipient phone number not in allowed list"

**Solution**: Add the phone number in Meta dashboard:
1. Go to WhatsApp > Getting Started
2. Click "Add phone number" under "To"
3. Verify the number

### Error: "Invalid access token"

**Solution**:
1. Token expired (temporary tokens last 24 hours)
2. Generate permanent token from System Users
3. Update `.env`

### Error: "Phone number not registered"

**Solution**:
1. Recipient must have WhatsApp installed
2. Phone number must be correct E.164 format (+962792903538)

---

## ✅ Current Status vs Production

### With Test Setup (Now):
- ✅ Sending messages via Meta (not Twilio)
- ✅ FREE
- ⚠️ Can only send to verified test numbers
- ⚠️ 24-hour access token

### After Production Approval:
- ✅ Send to ANY WhatsApp number
- ✅ Permanent access token
- ✅ Professional business profile
- ✅ 1,000 FREE conversations/month
- ✅ Recipients receive WITHOUT joining! ✅

---

## 🚀 Next Steps

1. ✅ **Set up Meta WhatsApp** (follow steps above)
2. ✅ **Test with your phone**
3. ✅ **Apply for production access** (when ready)
4. ✅ **Never use Twilio for WhatsApp again!**

**You now have WhatsApp working WITHOUT Twilio!** 🎉
