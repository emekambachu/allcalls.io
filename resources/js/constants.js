
let typeA = [
    31,
    32,
    33,
    34,
    35,
    36,
    37,
    38,
    39,
    0,
    1,
    2,
    3,
    4,
    5,
    6,
    7,
    8,
    9,
    10,
    40,
    19,
    20,
    21,
    22,
    23,
    41,
    29,
    30,
  ];
  let typeB = [
    31,
    32,
    33,
    34,
    35,
    36,
    37,
    38,
    39,
    0,
    2,
    14,
    6,
    7,
    8,
    9,
    10,
    15,
    16,
    40,
    19,
    20,
    21,
    22,
    23,
    41,
    29,
    30,
  ];
  let typeC = [
    31,
    32,
    33,
    34,
    35,
    36,
    37,
    38,
    39,
    0,
    1,
    2,
    17,
    3,
    4,
    5,
    6,
    7,
    8,
    9,
    10,
    15,
    16,
    40,
    19,
    20,
    21,
    22,
    23,
    41,
    29,
    30,
  ];
  let typeD = [
    31,
    32,
    33,
    34,
    35,
    36,
    37,
    38,
    39,
    0,
    1,
    2,
    11,
    12,
    13,
    6,
    7,
    8,
    9,
    10,
    15,
    16,
    40,
    19,
    20,
    21,
    22,
    23,
    41,
    29,
    30,
  ];
  let typeE = [
    31,
    32,
    33,
    34,
    35,
    36,
    37,
    38,
    39,
    0,
    1,
    2,
    11,
    12,
    13,
    6,
    7,
    8,
    9,
    10,
    40,
    19,
    31,
    20,
    21,
    22,
    23,
    41,
    29,
    30,
  ];
  let typeF = [
    31,
    32,
    33,
    34,
    35,
    36,
    37,
    38,
    39,
    0,
    2,
    14,
    18,
    6,
    7,
    8,
    9,
    10,
    15,
    16,
    40,
    19,
    31,
    20,
    21,
    22,
    23,
    41,
    29,
    30,
  ];
let companies = {
    "AETNA/CVS": {
      "Individual Whole Life": {
        questions: typeA,
      },
    },
    AIG: {
      GIWL: {
        questions: typeA,
      },
      "Power 10 Protector": {
        questions: typeB,
      },
      "Power 7 Protector": {
        questions: typeB,
      },
      "SimpliNow Legacy": {
        questions: typeA,
      },
    },
    "American Amicable": {
      "Easy Term": {
        questions: typeA,
      },
      "Express UL": {
        questions: typeA,
      },
      "Family Choice": {
        questions: typeA,
      },
      "Home Protector": {
        questions: typeA,
      },
      OBA: {
        questions: typeA,
      },
      "Security Protector": {
        questions: typeA,
      },
      "Senior Choice": {
        questions: typeA,
      },
      "Survivor Protector": {
        questions: typeA,
      },
      "Term Made Simple": {
        questions: typeA,
      },
    },
    "American Equity": {
      "AssetShield 10 with Enhancements": {
        questions: typeB,
      },
      "AssetShield 5 with Enhancements": {
        questions: typeB,
      },
      "AssetShield 7 with Enhancements": {
        questions: typeB,
      },
      "Bonus Gold": {
        questions: typeB,
      },
      "CA AssetShield 5": {
        questions: typeB,
      },
      "CA AssetShield 9": {
        questions: typeB,
      },
      "CA Destinations 9": {
        questions: typeB,
      },
      "CA IncomeShield 7": {
        questions: typeB,
      },
      "CA IncomeShield 9 With LIBR": {
        questions: typeB,
      },
      "CA IncomeShield 9 Without LIBR": {
        questions: typeB,
      },
      "EstateShield 10": {
        questions: typeB,
      },
      "FL Retirement Gold": {
        questions: typeB,
      },
      "FlexShield 10": {
        questions: typeB,
      },
      "Guarantee 6": {
        questions: typeB,
      },
      "Guarantee Series (5,6,7)": {
        questions: typeB,
      },
      "GuaranteeShield 3": {
        questions: typeB,
      },
      "GuaranteeShield 5": {
        questions: typeB,
      },
      "Immediate Annuity": {
        questions: typeB,
      },
      "IncomeSheild 10 With LIBR": {
        questions: typeB,
      },
      "IncomeShield 10 Without LIBR": {
        questions: typeB,
      },
      "IncomeShield 7": {
        questions: typeB,
      },
      "Retirement Gold": {
        questions: typeB,
      },
    },
    Americo: {
      "Advantage Whole Life": {
        questions: typeA,
      },
      Continuation: {
        questions: typeA,
      },
      "Eagle Guaranteed Series": {
        questions: typeA,
      },
      "Eagle Premier Series": {
        questions: typeA,
      },
      "Payment Protector Continuation": {
        questions: typeA,
      },
      "Term /ADB": {
        questions: typeA,
      },
      "Term 100": {
        questions: typeA,
      },
      "Term 125": {
        questions: typeA,
      },
      "Term CBO-100": {
        questions: typeA,
      },
      "Term CBO-50": {
        questions: typeA,
      },
      "Term Payment Protector": {
        questions: typeA,
      },
    },
    Ameritas: {
      "Access Whole Life": {
        questions: typeC,
      },
      "Access Whole Life Simplified": {
        questions: typeC,
      },
      "Compass Flexible Premium": {
        questions: typeB,
      },
      "Excel Essential": {
        questions: typeD,
      },
      "FLX Living Benefits IUL": {
        questions: typeD,
      },
      "Growth 10 Pay Whole Life": {
        questions: typeC,
      },
      "Growth Index UL": {
        questions: typeC,
      },
      "Growth Whole Life": {
        questions: typeC,
      },
      "Value Plus Index UL": {
        questions: typeC,
      },
      "Value Plus Survivor IUL": {
        questions: typeC,
      },
      "Value Plus Term": {
        questions: typeA,
      },
      "Value Plus UL": {
        questions: typeC,
      },
      "Value Plus Whole Life": {
        questions: typeC,
      },
      "Value Plus Whole Life Under 25K": {
        questions: typeC,
      },
    },
    "Assurant Life": {
      "Level Benefit Whole Life": {
        questions: typeA,
      },
      "Modified Benefit Whole Life": {
        questions: typeA,
      },
    },
    Athene: {
      "Agility 10": {
        questions: typeB,
      },
      "Agility 7": {
        questions: typeB,
      },
      "Ascent Series": {
        questions: typeB,
      },
      "Max Rate 3": {
        questions: typeB,
      },
      "Max Rate 5": {
        questions: typeB,
      },
      "Max Rate 7": {
        questions: typeB,
      },
      "Performance Elite 10": {
        questions: typeB,
      },
      "Performance Elite 15": {
        questions: typeB,
      },
      "Performance Elite 7": {
        questions: typeB,
      },
      "Single Premium Immediate": {
        questions: typeB,
      },
    },
    CFG: {
      "Dignified Choice Series": {
        questions: typeA,
      },
      "SafeShield Simplified Issue": {
        questions: typeA,
      },
    },
    "Columbus Life": {
      "Advantage Single Premium Fixed Indexed": {
        questions: typeB,
      },
      "Expedition Survivorship IUL": {
        questions: typeD,
      },
      "Explorer Plus UL": {
        questions: typeE,
      },
      "Indexed Explorer Plus UL": {
        questions: typeD,
      },
      "Nautical Term": {
        questions: typeA,
      },
      "Voyager UL": {
        questions: typeA,
      },
    },
    EquiTrust: {
      "Certainty Select": {
        questions: typeB,
      },
      ChoiceFour: {
        questions: typeB,
      },
      "Confidence Income Annuity": {
        questions: typeB,
      },
      "MarketForce Bonus Index": {
        questions: typeB,
      },
      "MarketMax Index": {
        questions: typeB,
      },
      "MarketPower Bonus Index": {
        questions: typeB,
      },
      "MarketSeven Index": {
        questions: typeB,
      },
      "MarketTen Bonus Index": {
        questions: typeB,
      },
      "MarketValue Index": {
        questions: typeB,
      },
    },
    Ethos: {
      "Ethos Final Expense": {
        questions: typeA,
      },
      "Ethos Term Life Prime (LGA)": {
        questions: typeC,
      },
      "Ethos Term Life Select (LGA-Elevated)": {
        questions: typeC,
      },
      "Ethos Term Life Spectrum (Ameritas)": {
        questions: typeC,
      },
      "TruStage Advantage Whole Life": {
        questions: typeC,
      },
      "TruStage Simplified Issue Term Life": {
        questions: typeC,
      },
      "TrueStage Guaranteed Acceptance Whole Life": {
        questions: typeA,
      },
    },
    "Fidelity & Guaranty Life": {
      "Accelerator Plus 10": {
        questions: typeB,
      },
      "Accelerator Plus 14": {
        questions: typeB,
      },
      "Accumulator Plus 10": {
        questions: typeB,
      },
      "Accumulator Plus 7": {
        questions: typeB,
      },
      "Dynamic Accumulator": {
        questions: typeB,
      },
      Everlast: {
        questions: typeD,
      },
      ExecuDex: {
        questions: typeD,
      },
      "Flex Accumulator": {
        questions: typeB,
      },
      "Guarantee-Platinum Series": {
        questions: typeB,
      },
      "Immediate Income": {
        questions: typeB,
      },
      Pathsetter: {
        questions: typeD,
      },
      "Performance Pro": {
        questions: typeB,
      },
      "Power Accumulator 10": {
        questions: typeA,
      },
      "Power Accumulator 7": {
        questions: typeB,
      },
      "Prosperity Elite": {
        questions: typeB,
      },
      "Retirement Pro": {
        questions: typeB,
      },
      "Safe Income Plus": {
        questions: typeB,
      },
    },
    Foresters: {
      "Advantage Plus Fully Underwritten": {
        questions: typeC,
      },
      "Advantage Plus Simplified Issue": {
        questions: typeC,
      },
      "BrightFuture Children's Whole Life": {
        questions: typeA,
      },
      "PlanRight Basic": {
        questions: typeA,
      },
      "PlanRight Preferred": {
        questions: typeA,
      },
      "PlanRight Standard": {
        questions: typeA,
      },
      Prepared: {
        questions: typeA,
      },
      "Smart UL Fully Underwritten": {
        questions: typeA,
      },
      "Smart UL Simplified Issue": {
        questions: typeA,
      },
      "Strong Foundation Simplified": {
        questions: typeA,
      },
      "Your Legacy": {
        questions: typeA,
      },
      "Your Term Medical": {
        questions: typeA,
      },
    },
    Gerber: {
      "Accident Protection": {
        questions: typeA,
      },
      "Gerber Life College Plan": {
        questions: typeB,
      },
      "Grow Up Plan": {
        questions: typeA,
      },
      "Grow Up Plan *Advance Payment*": {
        questions: typeA,
      },
      "Guaranteed Issue Whole Life": {
        questions: typeA,
      },
      "Simplified Senior Life": {
        questions: typeA,
      },
      "Whole Life Age 18-70": {
        questions: typeA,
      },
    },
    GILICO: {
      "Choice 4": {
        questions: typeB,
      },
      "Flex 10": {
        questions: typeB,
      },
      "Flex 12": {
        questions: typeB,
      },
      "Flex 7": {
        questions: typeB,
      },
      "FlexPlus 10": {
        questions: typeB,
      },
      "FlexPlus 5": {
        questions: typeB,
      },
      "FlexPlus 7": {
        questions: typeB,
      },
      "GILICO Form 1046": {
        questions: typeB,
      },
      "GILICO Form 1049": {
        questions: typeB,
      },
      "Guaranty 4": {
        questions: typeB,
      },
      "Guaranty 6": {
        questions: typeB,
      },
      "Guaranty 8": {
        questions: typeB,
      },
      "Guaranty Growth Builder": {
        questions: typeB,
      },
      "Guaranty Growth Plus": {
        questions: typeB,
      },
      "Guaranty Immediate Annuity": {
        questions: typeB,
      },
      "Guaranty Rate Lock": {
        questions: typeB,
      },
      SPDA: {
        questions: typeB,
      },
      "SPDA Deposits": {
        questions: typeB,
      },
      "Security Plus": {
        questions: typeB,
      },
      "Ultra Choice": {
        questions: typeB,
      },
      "Ultra Flex 10": {
        questions: typeB,
      },
      "Ultra Flex 7": {
        questions: typeB,
      },
      WealthChoice: {
        questions: typeB,
      },
    },
    "Government Personnel Mutual": {
      "20 Pay Whole Life": {
        questions: typeA,
      },
      "Equity Protector": {
        questions: typeA,
      },
      "Estate Builder": {
        questions: typeA,
      },
      "Estate Builder for Kids": {
        questions: typeA,
      },
      "Final Expense": {
        questions: typeA,
      },
      "Life Alliance Term": {
        questions: typeA,
      },
      "Life Alliance UL": {
        questions: typeA,
      },
      "Traditional Whole Life": {
        questions: typeA,
      },
    },
    "Great Western": {
      "Day One/Assurance Plus": {
        questions: typeA,
      },
      "Graded Benefit Plan": {
        questions: typeA,
      },
      "Guaranteed Assurance": {
        questions: typeA,
      },
      "The Great Assurance": {
        questions: typeA,
      },
    },
    HMA: {
      "HMA 10000": {
        questions: typeA,
      },
      "HMA 15000": {
        questions: typeA,
      },
      "HMA 20000": {
        questions: typeA,
      },
      "HMA 2500": {
        questions: typeA,
      },
      "HMA 25000": {
        questions: typeA,
      },
      "HMA 30000": {
        questions: typeA,
      },
      "HMA 40000": {
        questions: typeA,
      },
      "HMA 5000": {
        questions: typeA,
      },
      "HMA 50000": {
        questions: typeA,
      },
      "HMA 60000": {
        questions: typeA,
      },
      "HMA 7500": {
        questions: typeA,
      },
    },
    "John Hancock": {
      "Simple Term": {
        questions: typeA,
      },
    },
    "Lafayette Life": {
      "10 Pay Life WL": {
        questions: typeC,
      },
      "Contender WL": {
        questions: typeC,
      },
      "Continental Term Series": {
        questions: typeA,
      },
      "Group Marquis® Fixed Indexed Annuities": {
        questions: typeB,
      },
      "Heritage WL": {
        questions: typeC,
      },
      "Horizon Single Premium Immediate Annuity": {
        questions: typeB,
      },
      "Liberty WL": {
        questions: typeC,
      },
      "Marquis® Centennial Deferred Fixed Indexed Annuity": {
        questions: typeB,
      },
      "Marquis® Single Premium Fixed Indexed Annuity": {
        questions: typeB,
      },
      "Patriot WL": {
        questions: typeC,
      },
      "Protector Simplified WL": {
        questions: typeA,
      },
      "Sentinel WL": {
        questions: typeC,
      },
    },
    "Mutual of Omaha": {
      "Accum UL Fully Underwritten": {
        questions: typeA,
      },
      "Children's Whole Life": {
        questions: typeA,
      },
      "Critical Advantage Cancer": {
        questions: typeA,
      },
      "Critical Advantage Critical": {
        questions: typeA,
      },
      "Critical Advantage Heart Attack & Stroke": {
        questions: typeA,
      },
      "DI Mutual Income Solutions": {
        questions: typeA,
      },
      "Guaranteed Advantage": {
        questions: typeA,
      },
      "Income Advantage IUL": {
        questions: typeA,
      },
      "Indexed UL Express": {
        questions: typeA,
      },
      "Life Protection Advantage IUL": {
        questions: typeA,
      },
      "Living Promise Graded": {
        questions: typeA,
      },
      "Living Promise Level": {
        questions: typeA,
      },
      "Long-Term Care": {
        questions: typeA,
      },
      "Medicare Supplement": {
        questions: typeA,
      },
      "Medicare Supplement Basic": {
        questions: typeA,
      },
      "Medicare Supplement Extended Basic": {
        questions: typeA,
      },
      "Medicare Supplement High Deductible Basic": {
        questions: typeA,
      },
      "Medicare Supplement Plan A": {
        questions: typeA,
      },
      "Medicare Supplement Plan B": {
        questions: typeA,
      },
      "Medicare Supplement Plan C": {
        questions: typeA,
      },
      "Medicare Supplement Plan D": {
        questions: typeA,
      },
      "Medicare Supplement Plan F": {
        questions: typeA,
      },
      "Medicare Supplement Plan G": {
        questions: typeA,
      },
      "Medicare Supplement Plan M": {
        questions: typeA,
      },
      "Medicare Supplement Plan N": {
        questions: typeA,
      },
      "Mutual Dental Preferred": {
        questions: typeA,
      },
      "Mutual Dental Protection": {
        questions: typeA,
      },
      "Term Life Answers Fully Underwritten": {
        questions: typeA,
      },
      "Term Life Express Simplified Issue": {
        questions: typeA,
      },
    },
    "Nassau Re": {
      "Phoenix Personal Income": {
        questions: typeB,
      },
      "Remembrance Life": {
        questions: typeA,
      },
      "Safe Harbor Express": {
        questions: typeA,
      },
      "Safe Harbor Term": {
        questions: typeA,
      },
      "Simplicity IUL Simplified": {
        questions: typeA,
      },
    },
    NLG: {
      BasicSecure: {
        questions: typeA,
      },
      "FIT Certain Income": {
        questions: typeB,
      },
      "FIT Focus Growth": {
        questions: typeB,
      },
      "FIT Focus Income": {
        questions: typeB,
      },
      "FIT Horizon Growth": {
        questions: typeB,
      },
      "FIT Horizon Income": {
        questions: typeB,
      },
      "FIT Rewards Growth Select Income": {
        questions: typeF,
      },
      "FIT Secure Growth": {
        questions: typeF,
      },
      "FIT Select Income": {
        questions: typeF,
      },
      "Flexlife (2019)": {
        questions: typeD,
      },
      "LSW Term": {
        questions: typeA,
      },
      "PeakLife (2019)": {
        questions: typeD,
      },
      "SummitLife IUL": {
        questions: typeD,
      },
      SurvivorLife: {
        questions: typeD,
      },
      "Total Secure": {
        questions: typeA,
      },
    },
    "North American Co": {
      "Charter Plus 10": {
        questions: typeB,
      },
      "Charter Plus 14": {
        questions: typeB,
      },
      "Guarantee Choice 10": {
        questions: typeB,
      },
      "Guarantee Choice 3": {
        questions: typeB,
      },
      "Guarantee Choice 5": {
        questions: typeB,
      },
      "Guarantee Choice 7": {
        questions: typeB,
      },
      "NA Income": {
        questions: typeB,
      },
      "NAC Benefit Solutions 10": {
        questions: typeB,
      },
      "NAC Income Choice 10": {
        questions: typeB,
      },
      "NAC Versa Choice 10": {
        questions: typeB,
      },
      "Performance Choice 8": {
        questions: typeB,
      },
      "Prime Path Pro 10": {
        questions: typeB,
      },
      "Prime Path Pro 12": {
        questions: typeB,
      },
    },
    Occidental: {
      "EZ Term": {
        questions: typeA,
      },
      "Express UL": {
        questions: typeA,
      },
      "Family Choice": {
        questions: typeA,
      },
      "Home Protector": {
        questions: typeA,
      },
      OBA: {
        questions: typeA,
      },
      "Security Protector": {
        questions: typeA,
      },
      "Sr. Choice": {
        questions: typeA,
      },
      "Survivor Protector": {
        questions: typeA,
      },
      "Term Made Simple": {
        questions: typeA,
      },
    },
    Oxford: {
      "Assurance Final Expense": {
        questions: typeA,
      },
      "Assurance One Single Premium": {
        questions: typeA,
      },
      "Prosperity Select Single Premium": {
        questions: typeA,
      },
    },
    "Pet HMA": {
      "PHMA 10000": {
        questions: typeA,
      },
      "PHMA 15000": {
        questions: typeA,
      },
      "PHMA 20000": {
        questions: typeA,
      },
      "PHMA 2500": {
        questions: typeA,
      },
      "PHMA 5000": {
        questions: typeA,
      },
      "PHMA 7500": {
        questions: typeA,
      },
    },
    Prosperity: {
      "Family Freedom Term": {
        questions: typeA,
      },
      "New Vista Final Expense": {
        questions: typeA,
      },
      PrimeTerm: {
        questions: typeA,
      },
    },
    "Royal Neighbors": {
      "Graded Death Benefit": {
        questions: typeA,
      },
      "JETerm Fully Underwritten": {
        questions: typeA,
      },
      "JETerm Simplified Issue": {
        questions: typeA,
      },
      "Simplified Issue": {
        questions: typeA,
      },
    },
    "Security Benefit": {
      "Strategic Growth": {
        questions: typeA,
      },
      "Strategic Growth Plus": {
        questions: typeA,
      },
      "Total Value": {
        questions: typeA,
      },
    },
    "Sentinel Security Life": {
      "Accumulation Protector Plus": {
        questions: typeB,
      },
      "Guaranteed Income Annuity": {
        questions: typeB,
      },
      "Medicare Supplement / Select": {
        questions: typeA,
      },
      "Personal Choice Annuity": {
        questions: typeB,
      },
      "Personal Choice Plus 5 Year Index": {
        questions: typeB,
      },
      "Retirement Plus Multiplier": {
        questions: typeB,
      },
      "Summit Bonus Index": {
        questions: typeB,
      },
    },
    SILAC: {
      "Teton Bonus Series": {
        questions: typeF,
      },
    },
    "Tier One Aflac": {},
    TransAmerica: {
      "Accumulation UL": {
        questions: typeA,
      },
      "Final Expense Solutions": {
        questions: typeA,
      },
      "Financial Foundation": {
        questions: typeA,
      },
      "Lifetime Whole Life": {
        questions: typeA,
      },
      "Trendsetter LB": {
        questions: typeA,
      },
      "Trendsetter Super": {
        questions: typeA,
      },
    },
  };
  let company2 = {
    
  }

export  {companies,company2};
