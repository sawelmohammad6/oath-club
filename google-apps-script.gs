/**
 * OATH CLUB - Google Apps Script Web App
 *
 * Sheet1 (Members) columns:
 *   A:MemberID B:FullName C:FatherName D:MotherName E:DOB F:Phone G:Email
 *   H:Occupation I:Address J:BloodGroup K:TransactionID L:PhotoURL
 *   M:SubmitDate N:Status O:Position P:CommitteeRole
 *
 * "Settings" sheet: A:Key, B:Value
 * "Gallery" sheet: A:ID, B:ImageURL, C:Caption, D:UploadDate
 * "Committee" sheet: A:ID, B:Name, C:PhotoURL, D:Position, E:OrderIndex
 */

const SHEET_ID = "YOUR_GOOGLE_SHEET_ID_HERE";
const MEMBERS_SHEET = "Sheet1";
const SETTINGS_SHEET = "Settings";
const GALLERY_SHEET = "Gallery";
const COMMITTEE_SHEET = "Committee";

// Member column indices (0-based)
const COL = {
    MEMBER_ID: 0, FULL_NAME: 1, FATHER_NAME: 2, MOTHER_NAME: 3, DOB: 4,
    PHONE: 5, EMAIL: 6, OCCUPATION: 7, ADDRESS: 8, BLOOD_GROUP: 9,
    TRANSACTION_ID: 10, PHOTO_URL: 11, SUBMIT_DATE: 12, STATUS: 13,
    POSITION: 14, COMMITTEE_ROLE: 15
};

// Ensure extra sheets exist
function ensureSheets_() {
    const ss = SpreadsheetApp.openById(SHEET_ID);
    [SETTINGS_SHEET, GALLERY_SHEET, COMMITTEE_SHEET].forEach(name => {
        if (!ss.getSheetByName(name)) {
            const sheet = ss.insertSheet(name);
            if (name === SETTINGS_SHEET) sheet.appendRow(["Key", "Value"]);
            if (name === GALLERY_SHEET) sheet.appendRow(["ID", "ImageURL", "Caption", "UploadDate"]);
            if (name === COMMITTEE_SHEET) sheet.appendRow(["ID", "Name", "PhotoURL", "Position", "OrderIndex"]);
        }
    });
    // Add extra columns to Members sheet if missing
    const ms = ss.getSheetByName(MEMBERS_SHEET);
    if (ms && ms.getLastColumn() < 16) {
        const headers = ms.getRange(1, 1, 1, ms.getLastColumn()).getValues()[0];
        const newHeaders = [];
        if (headers.length < 15) newHeaders.push("Position");
        if (headers.length < 16) newHeaders.push("CommitteeRole");
        if (newHeaders.length > 0) {
            ms.getRange(1, headers.length + 1, 1, newHeaders.length).setValues([newHeaders]);
        }
    }
}

var _jsonpCallback_ = "";

function doGet(e) {
    ensureSheets_();
    _jsonpCallback_ = e && e.parameter && e.parameter.callback ? e.parameter.callback : "";
    const mode = e && e.parameter && e.parameter.mode ? e.parameter.mode : "members";

    try {
        if (mode === "settings") return handleGetSettings();
        if (mode === "gallery") return handleGetGallery();
        if (mode === "committee") return handleGetCommittee();
        return handleGetMembers(); // default: members
    } catch (err) {
        return sendJson({ error: err.toString() });
    }
}

function doPost(e) {
    ensureSheets_();
    try {
        const data = JSON.parse(e.postData.contents);
        const action = data.action || "register";
        if (action === "register") return handleRegisterMember(data);
        if (action === "approveMember" || action === "approveApplication") return handleApproveMember(data);
        if (action === "rejectApplication") return handleRejectApplication(data);
        if (action === "deleteApplication" || action === "deleteMember") return handleDeleteMember(data);
        if (action === "updateMember") return handleUpdateMember(data);
        if (action === "addMember") return handleAddMember(data);
        if (action === "getMembers") return handleGetMembers();
        if (action === "updateSettings" || action === "saveSettings" || action === "saveContact") return handleUpdateSettings(data);
        if (action === "addGallery") return handleAddGallery(data);
        if (action === "deleteGallery") return handleDeleteGallery(data);
        if (action === "updateGallery") return handleUpdateGallery(data);
        if (action === "addCommittee") return handleAddCommittee(data);
        if (action === "updateCommittee") return handleUpdateCommittee(data);
        if (action === "deleteCommittee") return handleDeleteCommittee(data);
        if (action === "reorderCommittee") return handleReorderCommittee(data);
        return sendJson({ error: "Unknown action: " + action });
    } catch (err) {
        return sendJson({ error: err.toString() });
    }
}

// ==================== MEMBERS ====================

function handleGetMembers() {
    const sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(MEMBERS_SHEET);
    const data = sheet.getDataRange().getValues();
    if (data.length < 2) return sendJson({ members: [] });
    const members = [];
    for (let i = 1; i < data.length; i++) {
        const r = data[i];
        members.push({
            id: r[COL.MEMBER_ID] || "", fullName: r[COL.FULL_NAME] || "",
            fatherName: r[COL.FATHER_NAME] || "", motherName: r[COL.MOTHER_NAME] || "",
            dob: r[COL.DOB] || "", phone: r[COL.PHONE] || "", email: r[COL.EMAIL] || "",
            occupation: r[COL.OCCUPATION] || "", address: r[COL.ADDRESS] || "",
            bloodGroup: r[COL.BLOOD_GROUP] || "", transactionId: r[COL.TRANSACTION_ID] || "",
            photoUrl: r[COL.PHOTO_URL] || "", submitDate: r[COL.SUBMIT_DATE] || "",
            status: r[COL.STATUS] || "", position: r[COL.POSITION] || "",
            committeeRole: r[COL.COMMITTEE_ROLE] || ""
        });
    }
    return sendJson({ members });
}

function handleRegisterMember(data) {
    const sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(MEMBERS_SHEET);
    const lastRow = sheet.getLastRow();
    let nextNumber = 1;
    if (lastRow >= 2) {
        const lastId = sheet.getRange(lastRow, 1).getValue();
        if (lastId) {
            const p = lastId.toString().split('-');
            if (p.length === 2) nextNumber = parseInt(p[1]) + 1;
        } else {
            const ids = sheet.getRange(2, 1, lastRow - 1, 1).getValues().flat();
            let maxN = 0;
            ids.forEach(id => {
                const p = (id || "").toString().split('-');
                if (p.length === 2) { const n = parseInt(p[1]); if (n > maxN) maxN = n; }
            });
            nextNumber = maxN + 1;
        }
    }
    const memberId = "OC-" + String(nextNumber).padStart(4, "0");
    const submitDate = new Date().toISOString();
    sheet.appendRow([
        memberId, data.fullName || "", data.fatherName || "", data.motherName || "",
        data.dob || "", data.phone || "", data.email || "", data.occupation || "",
        data.address || "", data.bloodGroup || "", data.transactionId || "",
        data.photoUrl || "", submitDate, "Pending", data.position || "", data.committeeRole || ""
    ]);
    return sendJson({ success: true, memberId: memberId, message: "Your Member ID is " + memberId });
}

function handleApproveMember(data) {
    const sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(MEMBERS_SHEET);
    const range = sheet.getDataRange();
    const vals = range.getValues();
    for (let i = 1; i < vals.length; i++) {
        if (vals[i][COL.MEMBER_ID] === data.memberId) {
            sheet.getRange(i + 1, COL.STATUS + 1).setValue("Approved");
            if (data.position) sheet.getRange(i + 1, COL.POSITION + 1).setValue(data.position);
            if (data.committeeRole) sheet.getRange(i + 1, COL.COMMITTEE_ROLE + 1).setValue(data.committeeRole);
            return sendJson({ success: true });
        }
    }
    return sendJson({ error: "Member not found" });
}

function handleDeleteMember(data) {
    const sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(MEMBERS_SHEET);
    const range = sheet.getDataRange();
    const vals = range.getValues();
    for (let i = 1; i < vals.length; i++) {
        if (vals[i][COL.MEMBER_ID] === data.memberId) {
            sheet.deleteRow(i + 1);
            return sendJson({ success: true });
        }
    }
    return sendJson({ error: "Member not found" });
}

function handleRejectApplication(data) {
    const sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(MEMBERS_SHEET);
    const range = sheet.getDataRange();
    const vals = range.getValues();
    for (let i = 1; i < vals.length; i++) {
        if (vals[i][COL.MEMBER_ID] === data.memberId) {
            sheet.getRange(i + 1, COL.STATUS + 1).setValue("Rejected");
            return sendJson({ success: true });
        }
    }
    return sendJson({ error: "Application not found" });
}

function handleUpdateMember(data) {
    const sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(MEMBERS_SHEET);
    const range = sheet.getDataRange();
    const vals = range.getValues();
    for (let i = 1; i < vals.length; i++) {
        if (vals[i][COL.MEMBER_ID] === data.memberId) {
            const row = i + 1;
            if (data.fullName !== undefined) sheet.getRange(row, COL.FULL_NAME + 1).setValue(data.fullName);
            if (data.phone !== undefined) sheet.getRange(row, COL.PHONE + 1).setValue(data.phone);
            if (data.email !== undefined) sheet.getRange(row, COL.EMAIL + 1).setValue(data.email);
            if (data.address !== undefined) sheet.getRange(row, COL.ADDRESS + 1).setValue(data.address);
            if (data.photoUrl !== undefined) sheet.getRange(row, COL.PHOTO_URL + 1).setValue(data.photoUrl);
            if (data.position !== undefined) sheet.getRange(row, COL.POSITION + 1).setValue(data.position);
            if (data.committeeRole !== undefined) sheet.getRange(row, COL.COMMITTEE_ROLE + 1).setValue(data.committeeRole);
            if (data.status !== undefined) sheet.getRange(row, COL.STATUS + 1).setValue(data.status);
            return sendJson({ success: true });
        }
    }
    return sendJson({ error: "Member not found" });
}

function handleAddMember(data) {
    const sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(MEMBERS_SHEET);
    const lastRow = sheet.getLastRow();
    let nextNumber = 1;
    if (lastRow >= 2) {
        const ids = sheet.getRange(2, 1, lastRow - 1, 1).getValues().flat();
        let maxN = 0;
        ids.forEach(id => {
            const p = (id || "").toString().split('-');
            if (p.length === 2) { const n = parseInt(p[1]); if (n > maxN) maxN = n; }
        });
        nextNumber = maxN + 1;
    }
    const memberId = "OC-" + String(nextNumber).padStart(4, "0");
    sheet.appendRow([
        memberId, data.fullName || "", "", "", "", data.phone || "", data.email || "",
        "", data.address || "", "", "", data.photoUrl || "", new Date().toISOString(),
        "Approved", data.position || "", data.committeeRole || ""
    ]);
    return sendJson({ success: true, memberId: memberId });
}

// ==================== SETTINGS ====================

function handleGetSettings() {
    const sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(SETTINGS_SHEET);
    const data = sheet.getDataRange().getValues();
    const settings = {};
    for (let i = 1; i < data.length; i++) {
        if (data[i][0]) settings[data[i][0].toString()] = data[i][1] || "";
    }
    return sendJson({ settings });
}

function handleUpdateSettings(data) {
    const sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(SETTINGS_SHEET);
    const existing = sheet.getDataRange().getValues();
    const keys = {};
    for (let i = 1; i < existing.length; i++) {
        if (existing[i][0]) keys[existing[i][0].toString()] = i + 1;
    }

    // Support both: data.settings = { key: val } AND flat data.everyKey = val
    var settingsData = data.settings || {};
    for (var key in data) {
        if (key === "action" || key === "settings") continue;
        settingsData[key] = data[key];
    }

    for (var key in settingsData) {
        if (keys[key]) {
            sheet.getRange(keys[key], 2).setValue(settingsData[key]);
        } else {
            sheet.appendRow([key, settingsData[key]]);
        }
    }
    return sendJson({ success: true });
}

// ==================== GALLERY ====================

function handleGetGallery() {
    const sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GALLERY_SHEET);
    const data = sheet.getDataRange().getValues();
    const items = [];
    for (let i = 1; i < data.length; i++) {
        items.push({ id: data[i][0] || "", imageUrl: data[i][1] || "", caption: data[i][2] || "", uploadDate: data[i][3] || "" });
    }
    return sendJson({ gallery: items });
}

function handleAddGallery(data) {
    const sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GALLERY_SHEET);
    const id = "G-" + new Date().getTime();
    sheet.appendRow([id, data.imageUrl || "", data.caption || "", new Date().toISOString()]);
    return sendJson({ success: true, id: id });
}

function handleDeleteGallery(data) {
    const sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GALLERY_SHEET);
    const range = sheet.getDataRange();
    const vals = range.getValues();
    for (let i = 1; i < vals.length; i++) {
        if (vals[i][0] === data.id) { sheet.deleteRow(i + 1); return sendJson({ success: true }); }
    }
    return sendJson({ error: "Gallery item not found" });
}

function handleUpdateGallery(data) {
    const sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GALLERY_SHEET);
    const range = sheet.getDataRange();
    const vals = range.getValues();
    for (let i = 1; i < vals.length; i++) {
        if (vals[i][0] === data.id) {
            if (data.caption !== undefined) sheet.getRange(i + 1, 3).setValue(data.caption);
            if (data.imageUrl !== undefined) sheet.getRange(i + 1, 2).setValue(data.imageUrl);
            return sendJson({ success: true });
        }
    }
    return sendJson({ error: "Gallery item not found" });
}

// ==================== COMMITTEE ====================

function handleGetCommittee() {
    const sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(COMMITTEE_SHEET);
    const data = sheet.getDataRange().getValues();
    const items = [];
    for (let i = 1; i < data.length; i++) {
        items.push({
            id: data[i][0] || "", name: data[i][1] || "", photoUrl: data[i][2] || "",
            position: data[i][3] || "", orderIndex: parseInt(data[i][4]) || 0
        });
    }
    items.sort((a, b) => a.orderIndex - b.orderIndex);
    return sendJson({ committee: items });
}

function handleAddCommittee(data) {
    const sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(COMMITTEE_SHEET);
    const id = "C-" + new Date().getTime();
    const orderIndex = data.orderIndex || (sheet.getLastRow());
    sheet.appendRow([id, data.name || "", data.photoUrl || "", data.position || "", orderIndex]);
    return sendJson({ success: true, id: id });
}

function handleUpdateCommittee(data) {
    const sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(COMMITTEE_SHEET);
    const range = sheet.getDataRange();
    const vals = range.getValues();
    for (let i = 1; i < vals.length; i++) {
        if (vals[i][0] === data.id) {
            const row = i + 1;
            if (data.name !== undefined) sheet.getRange(row, 2).setValue(data.name);
            if (data.photoUrl !== undefined) sheet.getRange(row, 3).setValue(data.photoUrl);
            if (data.position !== undefined) sheet.getRange(row, 4).setValue(data.position);
            if (data.orderIndex !== undefined) sheet.getRange(row, 5).setValue(data.orderIndex);
            return sendJson({ success: true });
        }
    }
    return sendJson({ error: "Committee member not found" });
}

function handleDeleteCommittee(data) {
    const sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(COMMITTEE_SHEET);
    const range = sheet.getDataRange();
    const vals = range.getValues();
    for (let i = 1; i < vals.length; i++) {
        if (vals[i][0] === data.id) { sheet.deleteRow(i + 1); return sendJson({ success: true }); }
    }
    return sendJson({ error: "Committee member not found" });
}

function handleReorderCommittee(data) {
    var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(COMMITTEE_SHEET);
    var range = sheet.getDataRange();
    var vals = range.getValues();
    if (!data.items) return sendJson({ error: "No items provided" });
    for (var r = 0; r < data.items.length; r++) {
        var item = data.items[r];
        for (var i = 1; i < vals.length; i++) {
            if (vals[i][0] === item.ID) {
                sheet.getRange(i + 1, 5).setValue(item.OrderIndex);
                break;
            }
        }
    }
    return sendJson({ success: true });
}

// ==================== HELPERS ====================

function sendJson(obj) {
    var output = JSON.stringify(obj);
    if (_jsonpCallback_) {
        return ContentService
            .createTextOutput(_jsonpCallback_ + "(" + output + ")")
            .setMimeType(ContentService.MimeType.JAVASCRIPT);
    }
    return ContentService
        .createTextOutput(output)
        .setMimeType(ContentService.MimeType.JSON);
}
