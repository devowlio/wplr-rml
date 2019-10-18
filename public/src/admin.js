import ReactDOM from 'react-dom';
import React from 'react';
import { hooks } from 'rml';
import './style.scss';
import rmlOpts from 'rmlopts';

const ICON_OBJ_LR_TYPE_ROOT = <span className="wplrRml-icon-root">Lr</span>;
const ICON_OBJ_LR_TYPE_COLLECTION = <i className="rmlicon-gallery" />;
const ICON_OBJ_LR_TYPE_FOLDER = <i className="rmlicon-archive" />;

/**
 * Since Real Media Library v4.6.0 the icon is hold in a string instead
 * of a frozen icon object.
 */
const needsIconString = rmlOpts.hasOwnProperty("lastQueried");

hooks.register('tree/node', node => {
    switch (node.properties.type) {
        case 10:
            node.icon = needsIconString ? 'lrRoot' : ICON_OBJ_LR_TYPE_ROOT;
            break;
        case 11:
            node.icon = needsIconString ? 'lrCollection' : ICON_OBJ_LR_TYPE_COLLECTION;
            break;
        case 12:
            node.icon = needsIconString ? 'lrFolder' : ICON_OBJ_LR_TYPE_FOLDER;
            break;
        default: break;
    }
});

/**
 * @see needsIconString description
 */
needsIconString && hooks.register('tree/node/icon', (result, icon) => {
    switch (icon) {
        case "lrRoot":
            result.icon = ICON_OBJ_LR_TYPE_ROOT;
            break;
        case "lrCollection":
            result.icon = ICON_OBJ_LR_TYPE_COLLECTION;
            break;
        case "lrFolder":
            result.icon = ICON_OBJ_LR_TYPE_FOLDER;
            break;
        default:
            break;
    }
});

hooks.register('rest/button/success/notice/issue3', () => window.location.reload());