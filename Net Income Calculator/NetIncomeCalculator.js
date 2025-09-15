function Calculate()
{
    let income = document.getElementById("income").value;
    let birthdate = document.getElementById("BirthDate").value;
    let children = document.getElementById("children").value;

    //Checking Income Validity

    if(income == "" || children == "" || birthdate == "")
    {
       alert("Fill all the requirements");
       return
    }

    if(!income.match(/^[0-9]+$/))
    {
       alert("Invalid income");
       return
    }

    if(!children.match(/^[0-9]+$/))
    {
       alert("Invalid No. Children");
       return
    }
 
    //Calculating age
    
    let today = new Date();
    let Today = today.toISOString().toString();

    let YearDiff = parseInt(Today.slice(0,4))- parseInt(birthdate.slice(0,4) );
    let MonthDiff = parseInt(Today.slice(5,7))- parseInt(birthdate.slice(5,7) );
    let DayDiff = parseInt(Today.slice(8,10))- parseInt(birthdate.slice(8,10) );
    
    if( MonthDiff<0 || DayDiff<0)
    {
        YearDiff-=1;
    }

    let Age = YearDiff;
    
    // Deciding Tax Tax_free and Discount

    let Tax = 0;
    let Tax_free = 0;
    let Discount = 0;

    if(income>40000)
    {
      Tax = 40/100;
      Tax_free = 2000;

      if( Age > 65 || Age < 25)
      {
        Discount = 5/100;
      }
    }
    else if(income>=15000)
    {
      Tax = 32/100;
      Tax_free = 4000;
  
      if( Age > 65 || Age < 25)
      {
        Discount = 10/100;
      }
    }
    else if (income>=8000) 
    {
      Tax = 20/100;
      Tax_free = 6500;

      if( Age > 65 || Age < 25)
      {
        Discount = 10/100;
      }
    }
    else
    {
       Tax = 10/100;
       Tax_free = 6000;

       if( Age > 65 || Age < 25)
       {
          Discount = 20/100;
       }
    }

    let Taxable_income = income - Tax_free;

    Tax = Tax*Taxable_income;

    if(parseInt(children)>4)
    {
        children="4";
    }

    Discount = Discount + (5/100)*parseInt(children);

    Tax = Tax - Tax*Discount;

    let Net_Income = income - Tax; 

    document.getElementById("NetIncome").value = Net_Income.toString();

};