# Excel File Structure - Quick Reference

## 📊 Required Headers (Case-Insensitive)

```
bank_name | bank_type | date | amount | utr | source_name | from_bank | to_bank | status | actual_closing | remark
```

---

## 📝 Row Examples

### ✅ DEPOSIT Row
```
bank_name: HDFC Bank
bank_type: bank
date: 2026-02-01
amount: 50000
utr: UTR001
source_name: Customer A
from_bank: [EMPTY]
to_bank: [EMPTY]
status: [EMPTY] ← Must be empty!
actual_closing: [EMPTY]
remark: Payment received
```

### ✅ WITHDRAWAL Row (Completed)
```
bank_name: ICICI Bank
bank_type: bank
date: 2026-02-01
amount: 30000
utr: UTR002
source_name: Vendor X
from_bank: [EMPTY]
to_bank: [EMPTY]
status: completed ← Required!
actual_closing: [EMPTY]
remark: Payment made
```

### ✅ WITHDRAWAL Row (Pending)
```
bank_name: Axis Bank
bank_type: bank
date: 2026-02-01
amount: 15000
utr: UTR003
source_name: Vendor Y
from_bank: [EMPTY]
to_bank: [EMPTY]
status: pending ← Required!
actual_closing: [EMPTY]
remark: Awaiting approval
```

### ✅ SETTLEMENT Row
```
bank_name: [EMPTY]
bank_type: [EMPTY]
date: 2026-02-01
amount: 20000
utr: UTR004
source_name: [EMPTY]
from_bank: HDFC Bank ← Required!
to_bank: ICICI Bank ← Required!
status: [EMPTY]
actual_closing: [EMPTY]
remark: Inter-bank transfer
```

### ✅ BANK CLOSING Row
```
bank_name: HDFC Bank
bank_type: bank
date: 2026-02-01
amount: [EMPTY]
utr: [EMPTY]
source_name: [EMPTY]
from_bank: [EMPTY]
to_bank: [EMPTY]
status: [EMPTY]
actual_closing: 450000 ← Required!
remark: Daily closing balance
```

---

## 🎯 Detection Logic

| Row Type | Required Fields | Detection Rule |
|----------|----------------|----------------|
| **Settlement** | from_bank, to_bank, amount | Has from_bank AND to_bank AND amount |
| **Closing** | bank_name, actual_closing | Has bank_name AND actual_closing |
| **Withdrawal** | bank_name, amount, status | Has bank_name AND amount AND status='pending' OR 'completed' |
| **Deposit** | bank_name, amount | Has bank_name AND amount (status empty) |

---

## ⚠️ Important Rules

### Status Field
- **Deposits**: Leave EMPTY or don't fill
- **Withdrawals**: Must be exactly `pending` or `completed`
- **Settlements**: Leave EMPTY
- **Closings**: Leave EMPTY

### Amount Field
- Must be numeric
- Must be greater than 0
- Use decimal point (not comma)
- Example: `50000.00` or `50000`

### Date Field
- Format: `YYYY-MM-DD` (e.g., `2026-02-01`)
- Or use Excel date format
- Must be valid date

### UTR Field
- Must be unique per bank per date
- Can be empty
- Recommended for tracking

### Bank Names
- Will be auto-created if don't exist
- Use consistent naming
- Case-sensitive

---

## 📥 Download Samples

### Basic Sample (10 rows)
http://localhost:8000/samples/smart-upload
- Quick test
- All transaction types
- Minimal data

### Comprehensive Sample (90 rows)
http://localhost:8000/samples/comprehensive
- Realistic scenario
- 20 deposits
- 25 withdrawals (15 completed + 10 pending)
- 20 settlements
- 25 bank closings
- 5 banks
- 5 days of data

---

## ✅ Validation Checklist

Before uploading, verify:

- [ ] Headers match exactly (case-insensitive OK)
- [ ] Deposits have NO status value
- [ ] Withdrawals have status = 'pending' or 'completed'
- [ ] Settlements have from_bank AND to_bank filled
- [ ] Closings have actual_closing filled
- [ ] All amounts are positive numbers
- [ ] All dates are valid
- [ ] UTRs are unique per bank per date
- [ ] No required fields are empty

---

## 🚫 Common Mistakes

| Mistake | Problem | Solution |
|---------|---------|----------|
| Status filled for deposits | Detected as withdrawal | Leave status EMPTY |
| Status = "done" or "complete" | Invalid value | Use "completed" exactly |
| Negative amounts | Validation fails | Use positive numbers only |
| Date as text "Feb 1" | Parse error | Use YYYY-MM-DD format |
| Duplicate UTRs | Insert fails | Use unique UTRs |
| from_bank = to_bank | Validation fails | Must be different banks |

---

## 📊 Sample Data Summary

### Comprehensive Sample (90 rows) Breakdown:

**Deposits (20 rows)**
- Customers: A through T
- Amounts: 30,000 to 85,000
- Dates: Feb 1-4, 2026
- Banks: HDFC, ICICI, Axis, SBI, Kotak

**Withdrawals - Completed (15 rows)**
- Vendors: A through O
- Amounts: 18,000 to 45,000
- Dates: Feb 1-3, 2026
- Status: completed

**Withdrawals - Pending (10 rows)**
- Vendors: P through Y
- Amounts: 12,000 to 31,000
- Dates: Feb 4-5, 2026
- Status: pending

**Settlements (20 rows)**
- Amounts: 25,000 to 50,000
- Dates: Feb 1-4, 2026
- Various bank combinations

**Bank Closings (25 rows)**
- 5 banks × 5 days
- Dates: Feb 1-5, 2026
- Realistic closing balances

---

## 🎯 Upload Process

1. **Download** comprehensive sample
2. **Review** the structure
3. **Modify** with your data (or use as-is for testing)
4. **Upload** via http://localhost:8000/upload
5. **Review** the upload report
6. **Verify** data in respective pages

---

**Need Help?** Check `BUG_FIX_SUMMARY.md` for detailed information.
