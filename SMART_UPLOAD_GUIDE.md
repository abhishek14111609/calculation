# Smart Upload - Quick Reference Guide

## 🚀 How to Use

### Step 1: Access Upload Page
Navigate to: **http://localhost:8000/upload**  
Or click: **"📤 Upload"** in the navigation menu

### Step 2: Download Sample Template (Optional)
Click: **"📥 Download Sample Template (All-in-One)"**

### Step 3: Prepare Your Excel File

Your Excel file should have these headers (case-insensitive):
```
bank_name | bank_type | date | amount | utr | source_name | from_bank | to_bank | status | actual_closing | remark
```

### Step 4: Add Your Data

**For Deposits:**
```
bank_name: HDFC Bank
date: 2026-02-01
amount: 50000
utr: UTR001
source_name: Customer A
remark: Payment received
```

**For Withdrawals:**
```
bank_name: ICICI Bank
date: 2026-02-01
amount: 30000
utr: UTR002
source_name: Vendor X
status: completed  ← REQUIRED for withdrawals
remark: Payment made
```

**For Settlements:**
```
from_bank: HDFC Bank  ← REQUIRED
to_bank: ICICI Bank   ← REQUIRED
date: 2026-02-01
amount: 20000
utr: UTR003
remark: Inter-bank transfer
```

**For Bank Closings:**
```
bank_name: HDFC Bank
date: 2026-02-01
actual_closing: 70000  ← REQUIRED for closings
remark: Daily closing balance
```

### Step 5: Upload File
1. Click "Choose File"
2. Select your Excel file
3. Click "Upload & Process"

### Step 6: Review Report
The system will show:
- ✅ Total rows processed
- ✅ Deposits inserted
- ✅ Withdrawals inserted
- ✅ Settlements inserted
- ✅ Closings updated
- ✅ Banks auto-created
- ⚠️ Errors (if any)

---

## 🎯 Detection Rules

The system automatically detects row type based on which fields are filled:

| Row Type | Detection Rule |
|----------|---------------|
| **Settlement** | Has `from_bank` AND `to_bank` AND `amount` |
| **Closing** | Has `actual_closing` AND `bank_name` AND `date` |
| **Withdrawal** | Has `status` AND `bank_name` AND `amount` |
| **Deposit** | Has `bank_name` AND `amount` (no status, no from_bank) |
| **Unknown** | Doesn't match any above → Skipped & reported |

---

## ✅ Validation Rules

- ✅ Amount must be > 0
- ✅ Date must be valid
- ✅ UTR must be unique per bank per date
- ✅ Status must be "pending" or "completed"
- ✅ from_bank and to_bank must be different
- ✅ Required fields must not be empty

---

## 💡 Tips

1. **Mix Everything**: You can have deposits, withdrawals, settlements, and closings all in ONE file
2. **Leave Empty Cells**: Empty cells are OK - just fill the required fields for each row type
3. **Auto-Create Banks**: If a bank doesn't exist, it will be created automatically
4. **Check Errors**: If some rows fail, valid rows are still processed
5. **Download Sample**: Use the sample template to see the correct format

---

## ⚠️ Common Errors

| Error | Cause | Solution |
|-------|-------|----------|
| "Unable to determine row type" | Missing required fields | Fill bank_name + amount (minimum) |
| "Duplicate UTR" | Same UTR exists for bank+date | Use unique UTRs or leave empty |
| "Invalid amount" | Negative or zero amount | Use positive numbers only |
| "Invalid date format" | Wrong date format | Use YYYY-MM-DD or Excel dates |
| "Invalid status" | Status not pending/completed | Use "pending" or "completed" only |

---

## 📊 Example Excel File

```
| bank_name  | date       | amount  | utr    | source_name | from_bank  | to_bank    | status    | actual_closing | remark           |
|------------|------------|---------|--------|-------------|------------|------------|-----------|----------------|------------------|
| HDFC Bank  | 2026-02-01 | 50000   | UTR001 | Customer A  |            |            |           |                | Deposit          |
| ICICI Bank | 2026-02-01 | 30000   | UTR002 | Vendor X    |            |            | completed |                | Withdrawal       |
|            | 2026-02-01 | 20000   | UTR003 |             | HDFC Bank  | ICICI Bank |           |                | Settlement       |
| HDFC Bank  | 2026-02-01 |         |        |             |            |            |           | 70000          | Closing balance  |
```

---

## 🔗 Quick Links

- **Upload Page**: http://localhost:8000/upload
- **Dashboard**: http://localhost:8000/
- **Deposits**: http://localhost:8000/deposits
- **Withdrawals**: http://localhost:8000/withdrawals
- **Settlements**: http://localhost:8000/settlements
- **Closings**: http://localhost:8000/closings

---

## 📞 Need Help?

Check the full documentation:
- `SMART_UPLOAD_IMPLEMENTATION.md` - Complete implementation details
- `README.md` - System overview
- `IMPLEMENTATION_SUMMARY.md` - What was built

---

**Happy Uploading! 🎉**
