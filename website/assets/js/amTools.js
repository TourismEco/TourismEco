function newRoot(id) {
    root = am5.Root.new(id);

    root.setThemes([
        am5themes_Animated.new(root)
    ]);

    return root
}

function addToRoot(root, fig, cursorObj) {
    g = root.container.children.push(fig)
    cursor = g.set("cursor", cursorObj.new(root, {}));
    cursor.lineY.set("visible", false);
    return g
}

function newXRenderer(root, obj) {
    xRenderer = obj.new(root, {});
    xRenderer.labels.template.setAll({
        fill:"#FFFFFF"
    });
    return xRenderer
}

function newYRenderer(root, obj) {
    yRenderer = obj.new(root, {});
    yRenderer.labels.template.setAll({
        fill:"#FFFFFF"
    });
    return yRenderer
}