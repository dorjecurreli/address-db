var FilterSearch = (function () {
        // Constructor
        function FilterSearch(containerId, filterContainerId, filters, usedFilters) {
            this.containerId = containerId;
            this.filterContainerId = filterContainerId;
            this.filters = filters;
            console.log('Filters: ', filters)
            this.usedFilters = usedFilters;
            console.log(usedFilters)
            this.selectedFilter = Object.keys(filters)[0];

            this.init();
        }

        FilterSearch.FILTER_TYPE_TEXT = "FILTER_TEXT";
        FilterSearch.FILTER_TYPE_SELECT = "FILTER_SELECT";


        // Execute
        FilterSearch.prototype.init = function () {
            if (!$('#' + this.containerId).length) {
                throw new Error('containerId not found!');
            }
            if (!$('#' + this.filterContainerId).length) {
                throw new Error('filterContainerId not found!');
            }
            this.createBaseNode();
            this.fillTypeSelect();
            this.updateFilterValueField();
            this.createUsedFilters();
            this.initEvents();
        };


        // Create input form with  multiselect dropdown
        FilterSearch.prototype.createBaseNode = function () {
            $('#' + this.containerId).append('<form class="m-0 p-3">\n' +
                '    <div class="input-group">\n' +
                '        <div class="input-group-prepend">\n' +
                '            <select name="search-filter-type" class="custom-select filter-select"></select>\n' +
                '        </div>\n' +
                '        <input type="text" name="search-filter-value" class="form-control filter-value">\n' +
                '        <div class="input-group-append" id="button-addon4">\n' +
                '            <input class="btn btn-primary" type="submit" value="Add Filter" href="/organizations/filters"/>\n' +
                '        </div>\n' +
                '     </div>\n' +
                '</form>');
        };

        //
        FilterSearch.prototype.initEvents = function () {
            var self = this;
            $('#' + this.containerId).find('select.filter-select').change(function (e) {
                self.selectedFilter = $(this).find('option:selected').val();
                self.updateFilterValueField();
            });
        };

        // Fill filter type into dropdown list
        FilterSearch.prototype.fillTypeSelect = function () {
            var self = this;
            $.each(this.filters, function (n, filter) {
                $('#' + self.containerId).find('select.filter-select').append('<option>' + filter.name + '</option>');
            });
        };

        // Switch input type based on filter type
        FilterSearch.prototype.updateFilterValueField = function () {
            var inputField = $('#' + this.containerId).find('.filter-value');
            switch (this.filters[this.selectedFilter].type) {
                case FilterSearch.FILTER_TYPE_TEXT:
                    inputField.replaceWith('<input name="search-filter-value" type="text" class="form-control filter-value">');
                    break;
                case FilterSearch.FILTER_TYPE_SELECT:
                    var selectField = $('<select name="search-filter-value" class="custom-select filter-value"></select>');
                    $.each(this.filters[this.selectedFilter].values, function (key, value) {
                        console.log(key, value)
                        selectField.append('<option value="' + key + '">' + value + '</option>');
                    });
                    inputField.replaceWith(selectField);
                    break;
                default:
                    throw new Error('unknown type');
            }
        };


        // Create dynamic buttons on filter added to show and delete the used filter
        FilterSearch.prototype.createUsedFilters = function () {
            var self = this;
            $labelSet = false;
            $.each(self.usedFilters, function (filter, values) {
                if(values.length > 0 && $labelSet==false) {
                    $('#' + self.filterContainerId).append('<span class="small">Used Filters: </span>');
                    $labelSet = true;
                }
                $.each(values, function (i, value) {
                    var filterNode = $('<span class="badge badge-info p-1">' + filter + ': ' + self.getFilterLabel(filter, value) + ' <i style="cursor: pointer;" class="fa fa-times-circle"></i></span> ');
                    $('#' + self.filterContainerId).append(filterNode).append(' ');
                    filterNode.data('filter', filter);
                    filterNode.data('value', value);
                    filterNode.find('i').click(function () {
                        location.href = location.href.split('?')[0] + '?' + $.param({
                            'delete-search-filter-type': $(this).parent().data('filter'),
                            'delete-search-filter-value': $(this).parent().data('value')
                        });
                    });
                });
            });
            if (Object.keys(self.usedFilters).length > 0) {
                var deleteAllfilterNode = $('<span class="badge badge-danger p-1" style="cursor: pointer;">Alle löschen <i class="fa fa-times-circle"></i></span> ');
                $('#' + self.filterContainerId).append(deleteAllfilterNode).append(' ');
                deleteAllfilterNode.click(function () {
                    location.href = location.href.split('?')[0] + '?' + $.param({
                        'delete-all-search-filter': 'true'
                    });
                });
            }
        };


        FilterSearch.prototype.getFilterLabel = function (filter, value) {
            switch (this.filters[filter].type) {
                case FilterSearch.FILTER_TYPE_TEXT:
                    return value;
                    break;
                case FilterSearch.FILTER_TYPE_SELECT:
                    return this.filters[filter].values[value];
                    break;
                default:
                    throw new Error('unknown filter');
            }
        };

        return FilterSearch;

    }
)();
