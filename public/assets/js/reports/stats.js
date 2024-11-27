  // Fetch data from the stats endpoint
  fetch('/stats')
  .then(response => response.json())
  .then(data => {
      // Populate the data into the respective elements
      document.getElementById('todayCollection').textContent = `₹${data.todayCollection}`;
      document.getElementById('totalInvoiceToday').textContent = data.totalInvoiceToday;
      document.getElementById('totalCompleteOrder').textContent = data.totalCompleteOrder;
      document.getElementById('totalRejectOrder').textContent = data.totalRejectOrder;
  })
  .catch(error => {
      console.error('Error fetching stats:', error);
  });
