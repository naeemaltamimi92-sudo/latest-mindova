# WhatsApp Business API Setup Guide for Mindova

## Prerequisites

- Meta Business Account (You have: `1210897034286594`)
- Meta App ID (You have: `857454440222813`)
- A phone number for WhatsApp Business

---

## Step 1: Create a Business Type App

Your current app may not support WhatsApp. Create a new one:

1. **Go to:** https://developers.facebook.com/apps/create/
2. **Select:** "Other" → Click "Next"
3. **Select:** "Business" → Click "Next"
4. **App Name:** `Mindova WhatsApp`
5. **Contact Email:** Your email
6. **Business Account:** Select `1210897034286594`
7. **Click:** "Create App"

---

## Step 2: Add WhatsApp Product

1. **In your new app dashboard**, look at the left sidebar
2. **Click:** "Add Product" or find the "+" button
3. **Find:** "WhatsApp" in the list
4. **Click:** "Set Up"

---

## Step 3: Get Started with WhatsApp

After adding WhatsApp product:

1. **Click:** "WhatsApp" → "Getting Started" in sidebar
2. **You'll see:**
   - Temporary Access Token
   - Phone Number ID
   - WhatsApp Business Account ID

---

## Step 4: Add a Phone Number

### Option A: Use Test Number (Free)
- Meta provides a test number for development
- Limited to 5 recipients
- Good for testing

### Option B: Add Your Business Number
1. Go to WhatsApp → "Configuration"
2. Click "Add Phone Number"
3. Enter your business phone
4. Verify via SMS/Voice code
5. Complete business verification if required

---

## Step 5: Configure Webhook

1. **Go to:** WhatsApp → Configuration
2. **Webhook URL:** `https://yourdomain.com/api/whatsapp/webhook`
3. **Verify Token:** `mindova_whatsapp_verify_2024`
4. **Click:** "Verify and Save"

### Subscribe to Webhook Fields:
- ✅ `messages`
- ✅ `message_deliveries`
- ✅ `message_reads`

---

## Step 6: Get Permanent Access Token

Temporary tokens expire in 24 hours. Get a permanent one:

1. **Go to:** Business Settings → System Users
2. **Create:** New System User (Admin role)
3. **Add Assets:** Select your WhatsApp Business Account
4. **Generate Token:** Select permissions:
   - `whatsapp_business_management`
   - `whatsapp_business_messaging`
5. **Copy:** The generated token (save securely!)

---

## Step 7: Update Your .env File

```env
# Meta App Credentials
META_APP_ID=your_new_app_id
META_APP_SECRET=your_new_app_secret

# WhatsApp Configuration
WHATSAPP_PHONE_NUMBER_ID=your_phone_number_id
WHATSAPP_BUSINESS_ACCOUNT_ID=your_business_account_id
WHATSAPP_ACCESS_TOKEN=your_permanent_token
WHATSAPP_VERIFY_TOKEN=mindova_whatsapp_verify_2024
```

---

## Step 8: Create Message Templates

Before sending template messages, create them in Meta:

1. **Go to:** WhatsApp → Message Templates
2. **Click:** "Create Template"
3. **Create these templates:**

### Template 1: Welcome Message
- **Name:** `mindova_welcome`
- **Category:** Marketing
- **Language:** English
- **Body:**
  ```
  Welcome to Mindova, {{1}}! 🎉

  You've joined our community of innovators. Start exploring challenges and connect with talented teams.

  Visit: {{2}}
  ```

### Template 2: Task Assignment
- **Name:** `mindova_task_assignment`
- **Category:** Utility
- **Language:** English
- **Body:**
  ```
  New Task Assigned! 📋

  Task: {{1}}
  Challenge: {{2}}
  Deadline: {{3}}

  View details: {{4}}
  ```

### Template 3: Team Invitation
- **Name:** `mindova_team_invitation`
- **Category:** Utility
- **Language:** English
- **Body:**
  ```
  Team Invitation! 👥

  You've been invited to join a team for "{{1}}".

  Accept or decline: {{2}}
  ```

---

## Step 9: Test Your Integration

### Test via Artisan Command:

```bash
php artisan tinker
```

```php
$service = app(\App\Services\WhatsAppCloudService::class);

// Check if configured
$service->isConfigured();

// Get status
$service->getStatus();

// Send test message (to your own number)
$service->sendTextMessage('966501234567', 'Hello from Mindova!');
```

---

## Troubleshooting

### "WhatsApp product not available"
- Create a new Business-type app
- Ensure business is verified

### "Invalid access token"
- Token may have expired
- Generate new System User token

### "Phone number not registered"
- Complete phone verification
- Wait for Meta approval

### "Template not approved"
- Check template status in Meta
- Modify rejected content
- Resubmit for review

---

## Useful Links

- [Meta Business Suite](https://business.facebook.com)
- [WhatsApp Business Platform](https://developers.facebook.com/docs/whatsapp)
- [Cloud API Documentation](https://developers.facebook.com/docs/whatsapp/cloud-api)
- [Message Templates](https://developers.facebook.com/docs/whatsapp/message-templates)

---

## Support

If you encounter issues:
1. Check Meta's [Status Page](https://developers.facebook.com/status/)
2. Review [Error Codes](https://developers.facebook.com/docs/whatsapp/cloud-api/support/error-codes)
3. Contact Meta Business Support

---

*Document created for Mindova Platform*
*Last updated: December 2025*
