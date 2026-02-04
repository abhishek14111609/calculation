# Bank/Exchange Reconciliation System - Complete Redesign Plan

## Executive Summary
Complete transformation from customer ledger system to daily bank/exchange reconciliation system matching Excel-based workflow.

## Phase 1: Database Schema Redesign ✓

### Tables to CREATE:
1. **banks** - Core entity (replaces customers)
   - id (PK)
   - name (unique)
   - type (enum: bank/exchange/wallet)
   - created_at

2. **deposits** - Money IN transactions
   - id (PK)
   - date
   - bank_id (FK → banks)
   - amount (decimal 15,2)
   - utr (varchar)
   - source_name (varchar) - ID/exchange name
   - remark (text, nullable)
   - created_at

3. **withdrawals** - Money OUT transactions
   - id (PK)
   - date
   - bank_id (FK → banks)
   - amount (decimal 15,2)
   - utr (varchar)
   - source_name (varchar)
   - status (enum: pending/completed)
   - remark (text, nullable)
   - created_at

4. **settlements** - Inter-bank transfers
   - id (PK)
   - date
   - from_bank_id (FK → banks)
   - to_bank_id (FK → banks)
   - amount (decimal 15,2)
   - utr (varchar)
   - remark (text, nullable)
   - created_at

5. **bank_closings** - Daily actual closing balances
   - id (PK)
   - date
   - bank_id (FK → banks)
   - actual_closing (decimal 15,2)
   - created_at
   - UNIQUE(date, bank_id)

### Tables to DROP:
- customers
- transactions
- upload_history (will recreate for new system)

## Phase 2: Models & Business Logic ✓

### New Models:
- Bank.php
- Deposit.php
- Withdrawal.php
- Settlement.php
- BankClosing.php

### Core Calculation Logic:
```
system_balance = 
  SUM(deposits) 
  - SUM(withdrawals WHERE status='completed')
  - SUM(settlements WHERE from_bank=X)
  + SUM(settlements WHERE to_bank=X)

difference = actual_closing - system_balance
pending_amount = SUM(withdrawals WHERE status='pending')
```

## Phase 3: Import/Export Services ✓

### Excel Import Logic:
- Multi-section detection
- Row-by-row classification
- Deposit rows → deposits table
- Withdrawal rows → withdrawals table
- Transfer rows → settlements table
- Closing rows → bank_closings table (upsert)

### Validation Rules:
- Strict header validation
- No duplicate UTR per bank per date
- No duplicate bank names
- Amount > 0
- Valid dates
- Valid bank references

### Export Logic:
- Respect all active filters
- Match visible data exactly
- Support CSV & Excel formats
- Include calculated columns

## Phase 4: Controller Redesign ✓

### ReconciliationController (replaces CalculationController):
- index() - Main reconciliation view
- deposits() - Deposit management
- withdrawals() - Withdrawal management
- settlements() - Settlement management
- closings() - Actual closing input
- uploadDeposits()
- uploadWithdrawals()
- uploadSettlements()
- exportReconciliation()
- exportDeposits()
- exportWithdrawals()

## Phase 5: Views & UI ✓

### Main Screen (reconciliation/index.blade.php):
- Date range filter
- Bank filter
- Reconciliation table:
  | Bank | Pay IN | Pay OUT | Settlements | System Balance | Actual Closing | Difference | Pending |

### Secondary Screens:
- deposits/index.blade.php - Deposit list + upload
- withdrawals/index.blade.php - Withdrawal list + status filter + upload
- settlements/index.blade.php - Settlement list + upload
- closings/index.blade.php - Closing balance input

### UI Principles:
- Clean, minimal design
- No customer concepts
- Filters always visible
- Upload buttons (not inline)
- Export matches view
- Responsive tables

## Phase 6: Routes ✓

```php
// Main reconciliation
GET  /                          → index
GET  /deposits                  → deposits.index
GET  /withdrawals               → withdrawals.index
GET  /settlements               → settlements.index
GET  /closings                  → closings.index

// Uploads
POST /deposits/upload           → uploadDeposits
POST /withdrawals/upload        → uploadWithdrawals
POST /settlements/upload        → uploadSettlements

// Exports
GET  /export/reconciliation     → exportReconciliation
GET  /export/deposits           → exportDeposits
GET  /export/withdrawals        → exportWithdrawals

// Closings
POST /closings/update           → updateClosing
```

## Phase 7: Cleanup ✓

### Files to DELETE:
- app/Models/Customer.php
- app/Models/Transaction.php
- app/Exports/CustomersExport.php
- app/Exports/TransactionsExport.php
- app/Imports/CustomersImport.php
- app/Imports/TransactionsImport.php
- app/Http/Controllers/CalculationController.php
- resources/views/calculation/*
- All customer-related migrations

### Files to CREATE:
- All new models (5)
- All new migrations (5)
- ReconciliationController
- Import classes (3)
- Export classes (3)
- All new views (5)

## Phase 8: Testing Checklist

- [ ] Upload deposits Excel
- [ ] Upload withdrawals Excel
- [ ] Upload settlements Excel
- [ ] System balance calculation accuracy
- [ ] Difference calculation accuracy
- [ ] Pending withdrawal calculation
- [ ] Filter by date range
- [ ] Filter by bank
- [ ] Export matches filtered view
- [ ] No duplicate UTR allowed
- [ ] No duplicate bank names
- [ ] Transaction rollback on error
- [ ] Large file handling via queue

## Success Criteria

✓ Zero customer/ledger concepts remain
✓ System mirrors Excel logic exactly
✓ Daily reconciliation is accurate
✓ Difference is always explainable
✓ Filters always match exports
✓ No duplicate data errors
✓ Simple, boring, reliable UI
✓ All calculations via SQL
✓ No PHP-side aggregation
✓ Pagination everywhere

## Implementation Order

1. Create new migrations (banks, deposits, withdrawals, settlements, bank_closings)
2. Create new models with relationships
3. Create ReconciliationController with core logic
4. Create import services with validation
5. Create export services
6. Create views (main reconciliation first)
7. Update routes
8. Delete old files
9. Test with sample data
10. Document usage

---
**Status**: Ready for implementation
**Estimated Time**: 2-3 hours
**Risk Level**: Medium (complete redesign)
