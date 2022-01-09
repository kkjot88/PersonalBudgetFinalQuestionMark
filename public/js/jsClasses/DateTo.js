class DateTo extends DateInput {    

    constructor(data) {
        super();                        
        this.jQuerryElement = $('#inputDateTo');        
        this.date = this.jQuerryElement.val();
        this.setOnChangeHandler();    
        this.setWrongOrderMsgHandler();    
        if (data) this.setData(data);
    }

    setData(data) {
        this.date = data.dateTo;
        this.isDisabled = data.datePickersDisabled;
    }

    checkIfDateOrderIsCorrect() {        
        let dateFrom = $("#inputDateFrom").val();
        if (this.date < dateFrom) {
            return false;
        }
        return true;
    }

}