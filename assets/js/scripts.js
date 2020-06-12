$("#crawler_form_Go").click(function () {
  $("#loader").removeClass("hidden").prependTo(document.form);
  $("#progress").removeClass("hidden");
});
// const fs = require('fs');
// const lighthouse = require('lighthouse');
// const chromeLauncher = require('chrome-launcher');
// const log = require('lighthouse-logger');

// (async () => {
//   log.setLevel('info');
//   const chrome = await chromeLauncher.launch({chromeFlags: ['--headless']});
//   const options = {output: 'html', onlyCategories: ['performance'], port: chrome.port};
//   const runnerResult = await lighthouse('https://example.com', options);

//   // `.report` is the HTML report as a string
//   const reportHtml = runnerResult.report;
//   fs.writeFileSync('lhreport.html', reportHtml);

//   // `.lhr` is the Lighthouse Result as a JS object
//   console.log('Report is done for', runnerResult.lhr.finalUrl);
//   console.log('Performance score was', runnerResult.lhr.categories.performance.score * 100);

//   await chrome.kill(); 
// })();
