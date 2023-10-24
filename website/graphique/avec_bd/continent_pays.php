<!-- Styles -->
<style>
#chartdiv {
  width: 100%;
  height: 500px;
  background-color: #2F3E46;
}
body{background-color: #52796F;}
</style>

<!-- Resources -->
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/hierarchy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

<!-- Chart code -->
<script>
am5.ready(function() {

// Create root element
// https://www.amcharts.com/docs/v5/getting-started/#Root_element
var root = am5.Root.new("chartdiv");

// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
root.setThemes([
  am5themes_Animated.new(root)
]);

// Create wrapper container
var container = root.container.children.push(
  am5.Container.new(root, {
    width: am5.percent(100),
    height: am5.percent(100),
    layout: root.verticalLayout
  })
);

// Create series
// https://www.amcharts.com/docs/v5/charts/hierarchy/#Adding
var series = container.children.push(
  am5hierarchy.Treemap.new(root, {
    singleBranchOnly: false,
    downDepth: 1,
    upDepth: -1,
    initialDepth: 2,
    valueField: "value",
    categoryField: "name",
    childDataField: "children",
    nodePaddingOuter: 0,
    nodePaddingInner: 0
  })
);

series.rectangles.template.setAll({
  strokeWidth: 2
});


// Generate and set data
// https://www.amcharts.com/docs/v5/charts/hierarchy/#Setting_data
var maxLevels = 2;
var maxNodes = 10;
var maxValue = 100;

var data = {
  name: "Root",
  children: [
    {
      name: "First",
      children: [
        {
          name: "ZA",
          value: 100
        },
        {
          name: "MA",
          value: 60
        },
        {
          name: "TN",
          value: 30
        }
      ]
    },
    {
      name: "Europe",
      children: [
        {
          name: "FR",
          value: 135
        },
        {
          name: "ES",
          value: 98
        },
        {
          name: "IT",
          value: 56
        }
      ]
    },
    {
      name: "Asie",
      children: [
        {
          name: "CN",
          value: 335
        },
        {
          name: "HK",
          value: 148
        },
        {
          name: "TR",
          value: 126
        }
      ]
    },
    {
      name: "Amerique",
      children: [
        {
          name: "US",
          value: 415
        },
        {
          name: "MX",
          value: 170
        },
        {
          name: "CA",
          value: 89
        }
      ]
    },
    {
      name: "Amerique du Sud",
      children: [
        {
          name: "PY",
          value: 687
        },
        {
          name: "AR",
          value: 148
        },
        {
          name: "CL",
          value: 26
        }
      ]
    }
    ,
    {
      name: "Oceanie",
      children: [
        {
          name: "AU",
          value: 687
        },
        {
          name: "NZ",
          value: 148
        },
        {
          name: "GU",
          value: 26
        }
      ]
    }
  ]
};

series.data.setAll([data]);
series.set("selectedDataItem", series.dataItems[0]);

// Make stuff animate on load
series.appear(1000, 100);

}); // end am5.ready()
</script>

<!-- HTML -->
<div id="chartdiv"></div>