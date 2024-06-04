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


  function populateProvinceFromDatalist(selectId, data,keterangan,) {
    var selectElement = $("#" + selectId);
    selectElement.empty();
  
    // Add placeholder option
    var placeholderOption = new Option(keterangan, '', true, true);
    selectElement.append(placeholderOption);
  
    // Populate options from data
    data.forEach(function (source) {
      var option = new Option(source.name, source.prov_region_code);
      selectElement.append(option);
    });
  
    // Initialize Select2
    selectElement.select2({
      placeholder: keterangan,
      width: '100%' // Adjust the width to fit the container
    });
  }
  
  



function datarem(){
  var tiperem = [
    { id: 1, name: "Request Update" },
    { id: 2, name: "Check Double" },
    { id: 3, name: "Wrong Input" },
    { id: 4, name: "Find More Data" },
      ];
    var column = [
    { id: 0, name: "Kolom Apa Saja" },
    { id: 1, name: "Tanggal First Offer" },
    { id: 2, name: "Tanggal Demo" },
    { id: 3, name: "Tanggal Presentasi" },
    { id: 4, name: "Tanggal Penawaran Terakhir" },
    { id: 5, name: "User Status" },
    { id: 6, name: "Direksi Status" },
    { id: 7, name: "Purchasing Status" },
    { id: 8, name: "Anggaran Status" },
    { id: 9, name: "Jenis Anggaran" },
    { id: 10, name: "Eta PO Date" },
    { id: 11, name: "Chance" },
      ];
    return {
        tiperem: tiperem,
        column: column
    };   
    
};
  
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

  function flash(type,message){
    $(".notify").html(`<div class="alert alert-`+type+` alert-dismissible fade show" role="alert">
                          `+message+`
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>`)
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

  var mx = 0;

    $(".drag").on({
      mousemove: function(e) {
        var mx2 = e.pageX - this.offsetLeft;
        if(mx) this.scrollLeft = this.sx + mx - mx2;
      },
      mousedown: function(e) {
        this.sx = this.scrollLeft;
        mx = e.pageX - this.offsetLeft;
      }
    });

    $(document).on("mouseup", function(){
      mx = 0;
    });



    