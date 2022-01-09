class IncomesTable extends FinancesTable {
 
    constructor(tableId, headers, data) {
        super();
        super.setTableId(tableId);
        super.setHeaders(headers);        
        if (data) this.setData(data);
    }

    setData (data) {   
        if (data.balance) {
            this.finances = data.balance.incomes
            this.sumOfEachCategory = data.balance.sumOfEachIncomeCategory
        }     
    }
    
    getEmptyMsg() {
        return "Nie posiadasz przychodów w tym okresie";
    }

}