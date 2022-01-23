class FinancesTable {

    tableId;
    headers;
    finances;
    sumOfEachCategory;

    setTableId(tableId) {
        this.tableId = tableId;
    }

    setHeaders(headers) {
        this.headers = headers;
    }

    formatTable() {
        $(`#${this.tableId}`).find('tbody').empty();
        
        if (this.checkIfFinancesAreNotEmpty()) {

            this.prepareTableRowForEachCategory()

            for (let [sumCategory,sumAmount] of Object.entries(this.sumOfEachCategory)) {
                let idOfTargetTableRowTr = this.replaceWhiteSpacesWithFloor(sumCategory);
                
                let thead = this.getThead();
                let tbody = this.getTbody(sumCategory);                
                let tfoot = this.getTfoot(sumAmount);                
    
                let table = $('<table>').addClass('table mb-0');
                table.append(thead);
                table.append(tbody);
                table.append(tfoot);
               
                let tdForTable = $('<td>').append(table).addClass('p-0');
                let trForTable = $('<tr>').append(tdForTable);
    
                trForTable.insertAfter(`#${idOfTargetTableRowTr}`);
            }
        } else {
            this.displayEmptyTableMsg();
        }              
    }

    replaceWhiteSpacesWithFloor(string) {
        return string.split(' ').join('_');
    }    

    checkIfFinancesAreNotEmpty() {        
        if (
                !(
                    jQuery.isEmptyObject(this.finances) && jQuery.isEmptyObject(this.sumOfEachCategory)
                )
            ) return true;

        return false;        
    }

    prepareTableRowForEachCategory() {
        for (let [sumCategory,sumAmount] of Object.entries(this.sumOfEachCategory)) {
            let sumCategoryId = this.replaceWhiteSpacesWithFloor(sumCategory);
            let tr = $('<tr>').attr('id',sumCategoryId);
            let tdSumCategory = $('<td>').text(sumCategory);         
            tr.append(tdSumCategory);
            $(`#${this.tableId}`).find('tbody').append(tr);      
        }      
    }
    
    displayEmptyTableMsg() {
        let emptyMsg = this.getEmptyMsg(); // child class method
        let td = $('<td>').text(emptyMsg);
        let tr = $('<tr>').append(td);
        $(`#${this.tableId}`).find('tbody').append(tr);
    }

    getThead () {
        let trForThead = $('<tr>');
        this.headers.forEach((header) => {
            let headerTd = $('<td>').text(header);
            trForThead.append(headerTd);
        });
        return $('<thead>').append(trForThead);
    }

    getTbody(sumCategory) {
        let tbody = $('<tbody>');
        let contentTrs = this.getArrayOfContentTrs(sumCategory);

        contentTrs.forEach((tr) => {
            tbody.append(tr);
        });

        return tbody;
    }

    getArrayOfContentTrs(sumCategory) {
        let contentTrs = [];
        let rowIndex = 1;
        this.finances.forEach((income) => {
            if (income.category == sumCategory) {
                contentTrs.push(this.getContentTr(income, rowIndex++));
            }
        });
        return contentTrs;
    }

    getContentTr(income, rowIndex) {
        let contentTr = $('<tr>');
        let id;                       
        for (let [i, [key,value]] of Object.entries(income).entries()) {
            if (i == 0) id = value;
            if (i > 0) {
                if (key=='category') {
                    contentTr.append($('<td>').text(rowIndex).addClass('w-auto'));
                } else {
                    contentTr.append($('<td>').text(value).addClass('col'));
                }
            }                     
        }
        contentTr.attr("financeid", id);
        return contentTr;
    }     

    getTfoot(sumAmount) {
        let tfootTdAmount = $('<td>').text(sumAmount).attr('colspan',4);
        let tfootTdTotal = $('<td>').html('<strong>razem :</strong>').addClass('text-end').attr('colspan',1);
        let tfootTr = $('<tr>').append(tfootTdTotal).append(tfootTdAmount);
        let tfoot = $('<tfoot>').append(tfootTr);
        return tfoot;
    }    
}