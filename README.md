# Bank/Exchange Reconciliation System

## Overview
A professional daily bank and exchange reconciliation system that tracks deposits, withdrawals, settlements, and closing balances to ensure accurate financial reconciliation.

## Core Concept
This system is designed to match real-world Excel-based daily operational control sheets used for bank/exchange reconciliation. It is **NOT** a customer ledger or accounting system.

## Key Features

### 1. **Multi-Bank Management**
- Track multiple banks, exchanges, and wallets
- Auto-create banks during Excel imports
- Support for different account types

### 2. **Transaction Types**
- **Deposits (Pay IN)**: Money received into bank accounts
- **Withdrawals (Pay OUT)**: Money paid out from bank accounts
- **Settlements**: Inter-bank transfers
- **Bank Closings**: Actual closing balances from bank statements

### 3. **Reconciliation Logic**
```
System Balance = 
    Total Deposits 
    - Total Completed Withdrawals
    - Settlements OUT
    + Settlements IN

Difference = Actual Closing - System Balance

Pending Amount = Sum of Pending Withdrawals
```

### 4. **Excel Import/Export**
- Bulk upload via Excel files
- Strict validation and error handling
- Transaction rollback on failures
- Sample templates available for download

### 5. **Advanced Filtering**
- Date range filtering
- Bank-specific filtering
- Amount range filtering
- Status filtering (for withdrawals)
- All filters apply to both UI and exports

## Database Schema

### Banks Table
- `id` - Primary key
- `name` - Unique bank name
- `type` - bank/exchange/wallet
- `created_at`, `updated_at`, `deleted_at`

### Deposits Table
- `id` - Primary key
- `date` - Transaction date
- `bank_id` - Foreign key to banks
- `amount` - Deposit amount
- `utr` - Unique Transaction Reference
- `source_name` - Source ID/exchange name
- `remark` - Optional notes
- Unique constraint: (bank_id, date, utr)

### Withdrawals Table
- `id` - Primary key
- `date` - Transaction date
- `bank_id` - Foreign key to banks
- `amount` - Withdrawal amount
- `utr` - Unique Transaction Reference
- `source_name` - Destination name
- `status` - pending/completed
- `remark` - Optional notes
- Unique constraint: (bank_id, date, utr)

### Settlements Table
- `id` - Primary key
- `date` - Transaction date
- `from_bank_id` - Source bank
- `to_bank_id` - Destination bank
- `amount` - Transfer amount
- `utr` - Unique Transaction Reference
- `remark` - Optional notes
- Unique constraint: (date, utr)

### Bank Closings Table
- `id` - Primary key
- `date` - Closing date
- `bank_id` - Foreign key to banks
- `actual_closing` - Actual closing balance from bank
- Unique constraint: (date, bank_id)

## Excel File Formats

### Deposits Template
| bank_name | bank_type | date | amount | utr | source_name | remark |
|-----------|-----------|------|--------|-----|-------------|--------|
| HDFC Bank | bank | 2026-02-01 | 50000.00 | UTR123 | Customer A | Payment received |

### Withdrawals Template
| bank_name | bank_type | date | amount | utr | source_name | status | remark |
|-----------|-----------|------|--------|-----|-------------|--------|--------|
| HDFC Bank | bank | 2026-02-01 | 30000.00 | UTR456 | Vendor X | completed | Payment made |

### Settlements Template
| date | from_bank | to_bank | amount | utr | remark |
|------|-----------|---------|--------|-----|--------|
| 2026-02-01 | HDFC Bank | ICICI Bank | 100000.00 | UTR789 | Inter-bank transfer |

## User Guide

### Main Dashboard
1. Navigate to the home page
2. Use filters to select date range and/or specific bank
3. View reconciliation summary showing:
   - Pay IN (deposits)
   - Pay OUT (completed withdrawals)
   - Net Settlements
   - System Balance
   - Actual Closing
   - Difference
   - Pending Withdrawals
4. Export filtered data to Excel

### Managing Deposits
1. Go to **Deposits** page
2. Download sample template if needed
3. Upload Excel file with deposit data
4. View, filter, and export deposits

### Managing Withdrawals
1. Go to **Withdrawals** page
2. Download sample template if needed
3. Upload Excel file with withdrawal data
4. Filter by status (pending/completed)
5. View, filter, and export withdrawals

### Managing Settlements
1. Go to **Settlements** page
2. Download sample template if needed
3. Upload Excel file with settlement data
4. Filter by source or destination bank
5. View, filter, and export settlements

### Recording Bank Closings
1. Go to **Closings** page
2. Enter bank, date, and actual closing balance
3. Click "Save Closing"
4. View history of all closing balances

## Technical Details

### Performance Optimizations
- All calculations performed at database level (SQL)
- No PHP-side aggregation
- Pagination on all list views (50 items per page)
- Indexed foreign keys and date columns
- Query optimization for large datasets

### Error Handling
- Database transactions for all imports
- Automatic rollback on validation failures
- Duplicate UTR detection
- Comprehensive validation rules
- User-friendly error messages

### Security Features
- Soft deletes on all tables
- No hard deletes allowed
- Unique constraints prevent duplicates
- File upload validation (type and size)
- SQL injection protection via Eloquent ORM

## API Endpoints

### Pages
- `GET /` - Main reconciliation dashboard
- `GET /deposits` - Deposits management
- `GET /withdrawals` - Withdrawals management
- `GET /settlements` - Settlements management
- `GET /closings` - Bank closings management

### Uploads
- `POST /deposits/upload` - Upload deposits Excel
- `POST /withdrawals/upload` - Upload withdrawals Excel
- `POST /settlements/upload` - Upload settlements Excel
- `POST /closings/update` - Update closing balance

### Exports
- `GET /export/reconciliation` - Export reconciliation data
- `GET /export/deposits` - Export filtered deposits
- `GET /export/withdrawals` - Export filtered withdrawals
- `GET /export/settlements` - Export filtered settlements

### Sample Downloads
- `GET /samples/deposits` - Download deposits template
- `GET /samples/withdrawals` - Download withdrawals template
- `GET /samples/settlements` - Download settlements template

## Installation

1. **Clone Repository**
   ```bash
   git clone <repository-url>
   cd calculation
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Setup**
   ```bash
   php artisan migrate:fresh
   ```

5. **Start Development Server**
   ```bash
   php artisan serve
   ```

6. **Access Application**
   Open browser to `http://localhost:8000`

## Maintenance

### Backup Database
```bash
php artisan db:backup
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### View Logs
```bash
tail -f storage/logs/laravel.log
```

## Support

For issues or questions, please contact the development team or create an issue in the repository.

## License

Proprietary - All rights reserved

---

**Version**: 2.0.0  
**Last Updated**: February 4, 2026  
**System Type**: Bank/Exchange Reconciliation System
