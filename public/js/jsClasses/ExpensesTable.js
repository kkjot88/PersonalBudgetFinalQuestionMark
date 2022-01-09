class ExpensesTable extends FinancesTable {

    constructor(tableId, headers, data) {
        super();
        super.setTableId(tableId);
        super.setHeaders(headers);        
        if (data) this.setData(data);
    }

    setData (data) {
        if (data.balance) {
            this.finances = data.balance.expenses
            this.sumOfEachCategory = data.balance.sumOfEachExpenseCategory
        }
    }     

    getEmptyMsg() {
        return "Nie posiadasz wydatków w tym okresie";
    }

}