function Estimate()
{
  mostfrequent="";
  Topfrequence = 0;
  frequence = 0;

  let input = document.getElementById("input").value;
  const words = input.split(" ");

  for (let word1 of words)
  {
    if ( word1.match(/^[a-zA-Z0-9]+$/) && word1.length >= 4 )
    {
        for (let word2 of words)
        {
            if ( word2.match(/^[a-zA-Z0-9]+$/) && word2.length >= 4 )
            {   
                if (word1 == word2)
                {
                    frequence = frequence + 1;
                }
            }
        }

        if ( frequence > Topfrequence )
        {
            Topfrequence = frequence;
            mostfrequent = word1;
        }

        frequence = 0;
    }
  }
  
  if(mostfrequent=="")
  {
    alert("Text body is empty, or has no valid words !");
  }
  else
  {
    alert("Most used word is : " + mostfrequent);
  }

};