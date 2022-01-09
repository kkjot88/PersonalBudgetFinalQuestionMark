class DateInput {

    date;
    isDisabled;
    jQuerryElement;    
    
    setOnChangeHandler() {
        let self = this;
        this.jQuerryElement.on('change', function() {            
            self.date = $(this).val(); 
            if (self.checkIfDateOrderIsCorrect()) {
                self.removeWrongOrderMsg();

                periodMethodCall = $("#postDates").serialize();  
                $.ajax({
                    type: "POST",
                    url: "/bilans/datePeriodMethodCall",
                    dataType: "json",
                    data: periodMethodCall,
                    success: formatPage
                });
            } else {
                self.displayWrongOrderMsg();
            }            
        });
    } 

    formatInput() {
        let newDate = (this.date) ? this.date : this.jQuerryElement.val();
        this.jQuerryElement.val(newDate).prop("disabled", this.isDisabled);
    }

    displayWrongOrderMsg() {
        this.removeWrongOrderMsg();
        
        let msgDiv = $('<div>').addClass('alert alert-danger mt-2');
        msgDiv.prop('id', 'dateOrderMsg');
        msgDiv.text("Nieprawidłowa kolejność dat");

        let targetDiv = $("#inputDateTo").parent();
        targetDiv.after(msgDiv);
    }

    removeWrongOrderMsg() {
        $("#dateOrderMsg").remove();
    }

    setWrongOrderMsgHandler() {
        let self = this;
        $("#selectPeriod").on('change', function() {
            self.removeWrongOrderMsg()
        });
    }

}