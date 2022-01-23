class DateFrom extends DateInput {

    constructor(data) {
        super();                                    
        this.jQuerryElement = $('#inputDateFrom');
        this.date = this.jQuerryElement.val();
        this.setOnChangeHandler();
        this.setWrongOrderMsgHandler();
        if (data) this.setData(data);
    }

    setData(data) {
        this.date = data.dateFrom;
        this.isDisabled = data.datePickersDisabled;
    }

    checkIfDateOrderIsCorrect() {
        let dateTo = $("#inputDateTo").val();
        if (this.date > dateTo) {
            return false;
        }
        return true;
    }

}