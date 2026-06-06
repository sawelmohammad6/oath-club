# Oath Club — Complete Setup Guide

## What This Guide Covers

1. Creating Google Sheet
2. Deploying Google Apps Script (the backend)
3. Connecting website to Google Sheet
4. Approving members
5. How it all works

---

## Step 1: Create the Google Sheet

This sheet stores all member applications.

1. Go to **https://sheets.new** (opens a blank sheet)
2. Click **Untitled spreadsheet** at top and rename it: `Oath Club Members`

3. **Copy the sheet ID** from the browser URL:
   ```
   https://docs.google.com/spreadsheets/d/1A2B3C4D5E6F7G8H9I0J/edit#gid=0
                                   ^^^^^^^^^^^^^^^^^^^^^^^^
                                   THIS PART IS THE SHEET ID
   ```
   Paste it somewhere — you'll need it in Step 4.

4. **Rename Sheet1** to `Sheet1` (it already is by default). Double-check the tab name at bottom-left.

5. **Create headers** — Copy and paste this into **Row 1** (cell A1):

   | A | B | C | D | E | F | G | H | I | J | K | L | M | N |
   |---|---|---|---|---|---|---|---|---|---|---|---|---|---|
   | MemberID | FullName | FatherName | MotherName | DOB | Phone | Email | Occupation | Address | BloodGroup | TransactionID | PhotoURL | SubmitDate | Status |

   Just type each header into the cells across Row 1 from A to N.

6. **Done.** The sheet should look like this:

   ![Sheet setup](https://imgur.com/placeholder)




---

## Step 2: Create the Google Apps Script

This script is the backend — it receives form submissions and returns approved members.

1. In your Google Sheet, click **Extensions** → **Apps Script**:

   ![Extensions > Apps Script](https://imgur.com/placeholder)

2. A new tab opens. Delete the placeholder code (`function myFunction() { ... }`).

3. **Paste the entire code** from the file `google-apps-script.gs` (in your project folder). Select all, copy, paste into the Apps Script editor.

   If you don't have the file, here's the code:

   ```javascript
   // ============================================
   // OATH CLUB - Google Apps Script Web App
   // ============================================

   const SHEET_ID = "YOUR_GOOGLE_SHEET_ID_HERE";
   const SHEET_NAME = "Sheet1";

   // Column indices (0-based)
   const COL = {
       MEMBER_ID: 0,
       FULL_NAME: 1,
       FATHER_NAME: 2,
       MOTHER_NAME: 3,
       DOB: 4,
       PHONE: 5,
       EMAIL: 6,
       OCCUPATION: 7,
       ADDRESS: 8,
       BLOOD_GROUP: 9,
       TRANSACTION_ID: 10,
       PHOTO_URL: 11,
       SUBMIT_DATE: 12,
       STATUS: 13
   };

   function doGet(e) {
       try {
           const sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(SHEET_NAME);
           const data = sheet.getDataRange().getValues();
           if (data.length < 2) {
               return sendResponse({ members: [] });
           }
           const members = [];
           for (let i = 1; i < data.length; i++) {
               const row = data[i];
               if (row[COL.STATUS] && row[COL.STATUS].toString().toLowerCase() === "approved") {
                   members.push({
                       memberId: row[COL.MEMBER_ID] || "",
                       fullName: row[COL.FULL_NAME] || "",
                       fatherName: row[COL.FATHER_NAME] || "",
                       motherName: row[COL.MOTHER_NAME] || "",
                       dob: row[COL.DOB] || "",
                       phone: row[COL.PHONE] || "",
                       email: row[COL.EMAIL] || "",
                       occupation: row[COL.OCCUPATION] || "",
                       address: row[COL.ADDRESS] || "",
                       bloodGroup: row[COL.BLOOD_GROUP] || "",
                       transactionId: row[COL.TRANSACTION_ID] || "",
                       photoUrl: row[COL.PHOTO_URL] || "",
                       submitDate: row[COL.SUBMIT_DATE] || "",
                       status: row[COL.STATUS] || ""
                   });
               }
           }
           members.sort((a, b) => {
               const numA = parseInt(a.memberId.split('-')[1]) || 0;
               const numB = parseInt(b.memberId.split('-')[1]) || 0;
               return numA - numB;
           });
           return sendResponse({ members });
       } catch (e) {
           return sendResponse({ error: e.toString() }, e.toString());
       }
   }

   function doPost(e) {
       try {
           const data = JSON.parse(e.postData.contents);
           const sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(SHEET_NAME);

           const lastRow = sheet.getLastRow();
           let nextNumber = 1;
           if (lastRow >= 2) {
               const lastMemberId = sheet.getRange(lastRow, COL.MEMBER_ID + 1).getValue();
               if (lastMemberId) {
                   const parts = lastMemberId.toString().split('-');
                   if (parts.length === 2) {
                       nextNumber = parseInt(parts[1]) + 1;
                   }
               } else {
                   const allIds = sheet.getRange(2, COL.MEMBER_ID + 1, lastRow - 1, 1).getValues();
                   let maxNum = 0;
                   allIds.forEach(row => {
                       const id = row[0] ? row[0].toString() : "";
                       const parts = id.split('-');
                       if (parts.length === 2) {
                           const num = parseInt(parts[1]);
                           if (num > maxNum) maxNum = num;
                       }
                   });
                   nextNumber = maxNum + 1;
               }
           }

           const memberId = "OC-" + String(nextNumber).padStart(4, "0");
           const submitDate = new Date().toISOString();

           sheet.appendRow([
               memberId,
               data.fullName || "",
               data.fatherName || "",
               data.motherName || "",
               data.dob || "",
               data.phone || "",
               data.email || "",
               data.occupation || "",
               data.address || "",
               data.bloodGroup || "",
               data.transactionId || "",
               data.photoUrl || "",
               submitDate,
               "Pending"
           ]);

           return sendResponse({ success: true, memberId: memberId });
       } catch (e) {
           return sendResponse({ error: e.toString() }, e.toString());
       }
   }

   function sendResponse(data, errorMsg) {
       const output = JSON.stringify(errorMsg ? { success: false, error: errorMsg } : data);
       return ContentService
           .createTextOutput(output)
           .setMimeType(ContentService.MimeType.JSON);
   }
   ```

4. **Replace the SHEET_ID** — Find this line in the code:
   ```javascript
   const SHEET_ID = "YOUR_GOOGLE_SHEET_ID_HERE";
   ```
   Replace `YOUR_GOOGLE_SHEET_ID_HERE` with the sheet ID you copied in Step 1. Example:
   ```javascript
   const SHEET_ID = "1A2B3C4D5E6F7G8H9I0J";
   ```

5. **Save the project** — Press `Ctrl + S` (or Cmd + S on Mac), or click the save icon.

6. **Name your project** — At top-left, click **Untitled project**, type `Oath Club Backend`, click **Rename**.





---

## Step 3: Deploy as Web App

This makes the script accessible from your website.

1. Click **Deploy** → **New deployment**:

   ![Deploy button](https://imgur.com/placeholder)

2. Click the gear icon (⚙️) next to "Select type" → choose **Web app**:

   ![Web app selection](https://imgur.com/placeholder)

3. Fill in the form:

   | Field | Value |
   |-------|-------|
   | **Description** | `Oath Club v1` |
   | **Execute as** | **Me** (youremail@gmail.com) |
   | **Who has access** | **Anyone** |

   **IMPORTANT:** Set "Who has access" to **Anyone**. If you set it to "Anyone within your organization", your website won't work.

4. Click **Deploy**.

5. **Authorize the app** — A new window pops up:
   - Click **Review permissions**
   - Choose your Google account
   - Click **Advanced** → **Go to Oath Club Backend (unsafe)** — (this is safe, it's your own code)
   - Click **Allow**

6. **Copy the Web App URL** — After authorization, you'll see:

   ```
   https://script.google.com/macros/s/AKfycbxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx/exec
   ```

   **Copy this URL completely.** This is your `GAS_URL`.

7. Click **Done**.





---

## Step 4: Connect Website to Google Sheet

1. Open the file **`js/config.js`** in your project folder.

2. Find this line:
   ```javascript
   const GAS_URL = "https://script.google.com/macros/s/YOUR_APPS_SCRIPT_ID/exec";
   ```

3. Replace the URL with the one you copied from Step 3:
   ```javascript
   const GAS_URL = "https://script.google.com/macros/s/AKfycbxxxxxxxxxxxxxxxxxxxxxxxxx/exec";
   ```

4. **Save the file.**





---

## Step 5: How Members Are Submitted & Approved

### When someone fills the form:

| Step | What Happens |
|------|-------------|
| 1 | User uploads photo → goes to **Cloudinary** (cloud storage) |
| 2 | Cloudinary returns a URL like `https://res.cloudinary.com/diboslkyo/image/upload/...` |
| 3 | Form data + photo URL sent to **Google Apps Script** |
| 4 | Google Apps Script writes a new row in your **Google Sheet** |
| 5 | New member has **Status = "Pending"** — not visible on website yet |

### To approve a member:

1. Open your **Google Sheet** (`Oath Club Members`)
2. Find the member's row
3. Go to column **N (Status)**
4. Change the value from `Pending` to **`Approved`**
5. The website automatically shows them in the members section after refreshing

> **That's it.** No admin panel needed. Just edit the cell in the sheet.

### To reject a member:
- Change Status to `Rejected` (or delete the row)





---

## Step 6: How the Website Loads Members

1. When someone visits your website, the file `js/app.js` automatically runs `loadApprovedMembers()`
2. This sends a GET request to your Apps Script URL
3. The script reads the Google Sheet, filters for **Status = "Approved"**, and returns the data as JSON
4. The website displays each member as a card with their photo and name

---

## Step 7: Make Changes to the Script

If you ever need to update the Apps Script:

1. Go to **https://script.google.com** → Open **Oath Club Backend**
2. Make your changes to the code
3. Click **Deploy** → **Manage deployments**
4. Find your deployment → Click the edit (pencil) icon
5. Click **Deploy** (creates a new version)
6. **IMPORTANT:** If the URL changes, update `GAS_URL` in `js/config.js`

---

## Troubleshooting

### Form submission fails / Error in console

**Problem:** No data appears in Google Sheet.

**Fix:**
1. Open your Apps Script project
2. At top, click **Executions** (clock icon)
3. Look for failed executions — they show the error message
4. Common issues:
   - Wrong `SHEET_ID` — double-check it
   - Sheet name is not `Sheet1` — rename the tab
   - Deployment permissions not set to "Anyone" — re-deploy

### Members not showing on website

**Fix:**
1. Check that column N says `Approved` (exact spelling, capital A)
2. Open browser DevTools (F12) → Console tab → look for errors
3. Test the URL directly: paste `GAS_URL?mode=members` in your browser — you should see JSON

### "Unknown API key" error on photo upload

**Fix:** Your Cloudinary upload preset `oath_club` is set to **Signed** mode. Change it:
1. Login at https://cloudinary.com
2. Settings → Upload → Upload Presets
3. Click Edit on `oath_club`
4. Change Signing Mode from `Signed` → `Unsigned`
5. Save

---

## File Structure Summary

```
Oath_Club/
│
├── index.html              ← Main website page
├── google-apps-script.gs   ← Backend code (paste into Apps Script)
├── js/
│   ├── config.js           ← Edit this: GAS_URL + Cloudinary settings
│   └── app.js              ← Website logic (no changes needed)
├── css/
│   └── style.css           ← Custom styles
└── assets/                 ← Images, banners, gallery photos
```

**You only need to edit:**
- `js/config.js` — to set `GAS_URL`
- `js/config.js` — to set Cloudinary details
- `assets/` — to replace images
- Your **Google Sheet** — to approve members
