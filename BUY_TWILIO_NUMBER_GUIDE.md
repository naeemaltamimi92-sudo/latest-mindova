# How to Buy a Twilio Phone Number for SMS

## Step-by-Step Instructions

### 1. Go to Twilio Phone Numbers Page

**URL**: https://console.twilio.com/us1/develop/phone-numbers/manage/search

### 2. Select Your Preferences

**Country**: Choose based on your users
- 🇯🇴 **Jordan** - if most users are in Jordan
- 🇸🇦 **Saudi Arabia** - if most users are in Saudi Arabia
- 🇺🇸 **USA** - works globally, usually cheapest

**Capabilities**: Check these boxes
- ✅ SMS
- ✅ MMS (optional, for future media messages)

**Number Type**:
- Select "Local" (cheapest, ~$1/month)

### 3. Click "Search"

Twilio will show available numbers.

### 4. Select a Number

- Click on any number you like
- Look for one that's easy to remember (optional)

### 5. Click "Buy"

- Cost: Usually $1-2/month
- Confirm purchase

### 6. Copy Your New Number

After purchase, copy the number (format: +1234567890)

### 7. Update Your .env File

Open `C:\xampp\htdocs\mindova\.env` and add:

```env
# SMS Configuration (add at the end)
TWILIO_SMS_FROM=+1234567890  # ← Paste your number here
TWILIO_SMS_ENABLED=true
```

### 8. Test It

Run this command:
```bash
php send_sms_test.php
```

You should see:
```
✅ SMS SENT SUCCESSFULLY!
```

Check your phone - you should receive an SMS! 📱

---

## If You Don't Have a Credit Card on Twilio

You need to add a payment method first:

1. Go to: https://console.twilio.com/us1/billing/manage-billing/billing-overview
2. Click "Add Payment Method"
3. Enter credit card details
4. Then go back to buy a number

---

## Cost Breakdown

| Item | Cost |
|------|------|
| Phone Number | $1-2/month |
| SMS to Jordan (+962) | ~$0.045/message |
| SMS to Saudi Arabia (+966) | ~$0.0165/message |
| SMS to USA (+1) | ~$0.0079/message |

**Example**: 100 SMS to Jordan = $1 + $4.50 = **$5.50/month**

---

## After Purchase

Once you add `TWILIO_SMS_FROM` to .env, the system will automatically:
- ✅ Send notifications via SMS
- ✅ Recipients receive immediately
- ✅ No joining required!
- ✅ Works while waiting for WhatsApp approval

---

**Status after this step**: Recipients can receive notifications via SMS without joining anything! ✅
