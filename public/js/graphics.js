
function makeDataSet(dataArray,type){
    let dataset=[];

    if(type=="newUsersPerDay"){
        dataset.push({
            label: "New user",
            data: giveMeDataByColumns(dataArray,"users"),
            //lineTension: 0.2,
            fill: false,
            backgroundColor: getRandomColor(),
            borderColor:getRandomColor()
        });
    }

    if(type=="activeUsers"){
        dataset.push({
            label: "Active users",
            data: giveMeDataByColumns(dataArray,"active"),
            //lineTension: 0.2,
            fill: false,
            backgroundColor: getRandomColor(),
            borderColor:getRandomColor()
        });
    }

    if(type=="verifiedUsers"){
        dataset.push({
            label: "Verified users",
            data: giveMeDataByColumns(dataArray,"verified"),
            //lineTension: 0.2,
            fill: false,
            backgroundColor: getRandomColor(),
            borderColor:getRandomColor()
        });
    }

    if(type=="newCompanyPerDay"){
        dataset.push({
            label: "New company",
            data: giveMeDataByColumns(dataArray,"company"),
            //lineTension: 0.2,
            fill: false,
            backgroundColor: getRandomColor(),
            borderColor:getRandomColor()
        });
    } 

    if(type=="activeCompany"){
        dataset.push({
            label: "Active company",
            data: giveMeDataByColumns(dataArray,"active"),
            //lineTension: 0.2,
            fill: false,
            backgroundColor: getRandomColor(),
            borderColor:getRandomColor()
        });
    } 

    if(type=="verifiedCompany"){
        dataset.push({
            label: "Verified company",
            data: giveMeDataByColumns(dataArray,"verified"),
            //lineTension: 0.2,
            fill: false,
            backgroundColor: getRandomColor(),
            borderColor:getRandomColor()
        });
    } 

    /*distint*/ 
    if(type=="testLanguage"){
        
        let language=noDuplicates(giveMeDataByColumns(dataArray,"language")); 
        
        for(let lang of language){        
            var data=compareData(dataArray,"language",lang);    

            dataset.push({
                label: lang,
                data: data,
                //lineTension: 0.2,
                fill: false,
                backgroundColor: getRandomColor(),
                borderColor:getRandomColor()
            });
        }
    }

    if(type=="jobsApplicantPerDay"){
        dataset.push({
            label: "Applications",
            data: giveMeDataByColumns(dataArray,"candidates"),
            //lineTension: 0.2,
            fill: false,
            backgroundColor: getRandomColor(),
            borderColor:getRandomColor()
        });
    }

    if(type=="activeJobs"){
        dataset.push({
            label: "Active jobs",
            data: giveMeDataByColumns(dataArray,"active_jobs"),
            //lineTension: 0.2,
            fill: false,
            backgroundColor: getRandomColor(),
            borderColor:getRandomColor()
        });
    } 

    if(type=="topCompaniesMonths"){
        let companies=noDuplicates(giveMeDataByColumns(topCompaniesTotal,"company"));
        
        for(let company of companies){
            let data=compareData(dataArray,"company",company)

            dataset.push({
                label: company,
                data: data,
                //lineTension: 0.2,
                fill: false,
                backgroundColor: getRandomColor(),
                borderColor:getRandomColor()
            });
        }       
    }

    return dataset;
}

function makeGraph(dataArray,title,typeChart){

    // document.querySelector("body").innerHTML+=
    //      `<div class="chart-div chart-container">
    // <canvas id="verifiedCompany" ></canvas>
    // </div>`
    //  ;

    let canvas=document.getElementById(`${title}`).getContext("2d");

    /*Thing that char will need*/
    var chartOptions = {
        legend: {
            display: true,
            position: 'top',
            labels: {
                boxWidth: 20,
                fontColor: 'black'
            }
        }
    };
    
    var chartData = {
        labels: noDuplicates(giveMeDataByColumns(dataArray,"time")),
        datasets: makeDataSet(dataArray,title)
    };

    /*Building the chart*/
    let chart= new Chart(canvas,{   
        type:`${typeChart}`,
        data:chartData,
        options:chartOptions
    });            
}

function giveMeDataByColumns(dataArray,column){
    var data=[];

    for(let items of dataArray){
        data.push(items[column]);        
    }
    return data;
}

function getRandomColor() {
    return '#'+(0x1000000+(Math.random())*0xffffff).toString(16).substr(1,6);
}

function noDuplicates(array) {
    array.splice(0, array.length, ...(new Set(array)))
    return array
};

function appearInMonth(dataArray,column,name){
    var month=[]
    for(let item of dataArray){
        if(item[column]==name){
            month.push(item["time"])
        }
    }
    return month
}

function compareData(dataArray,nameColumnCompare,option){
   
    var time=appearInMonth(dataArray,nameColumnCompare,option);
    var noMonths=[];
    var group=[];

    for(let item of dataArray){
        if(item[nameColumnCompare]==option){                
            group.push(item["data"]);                        

        }else if(!time.includes(item["time"]) && !noMonths.includes(item["time"])){
            noMonths.push(item["time"]);
            group.push(0);
        }                
    }

    return group;
}
