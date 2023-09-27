function populateSelectFromDatalist(selectId, data,keterangan,) {
    var selectElement = $("#" + selectId);
    selectElement.empty();
  
    // Add placeholder option
    var placeholderOption = new Option(keterangan, '', true, true);
    selectElement.append(placeholderOption);
  
    // Populate options from data
    data.forEach(function (source) {
      var option = new Option(source.name, source.id);
      selectElement.append(option);
    });
  
    // Initialize Select2
    selectElement.select2({
      placeholder: keterangan,
      width: '100%' // Adjust the width to fit the container
    });
  }
  
  function populateProductSelect(businessUnitId, categoryId,formId) {
    var productDataElement = document.getElementById('productData');
var getProductsUrl = productDataElement.getAttribute('data-url');

    $.ajax({
      url: getProductsUrl, // Replace with your endpoint to fetch products based on business unit and category
      method: "GET",
      data: {
        businessUnitId: businessUnitId,
        categoryId: categoryId
      },
      success: function (response) {
        populateSelectFromDatalist(formId, response.products,"Pilih Produk");
        // Add an empty option as a placeholder
        
      }
    });
  }


  function validateQuantity() {
    var quantityInput = document.getElementById('qtyinput');
    var quantityWarning = document.getElementById('quantityWarning');
  
    if (quantityInput.value < 1) {
      quantityWarning.style.display = 'block';
    } else {
      quantityWarning.style.display = 'none';
    }
  }

