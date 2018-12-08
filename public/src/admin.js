import ReactDOM from 'react-dom';
import React from 'react';
import { hooks } from 'rml';
import './style.scss';

const ICON_OBJ_LR_TYPE_ROOT = <span className="wplrRml-icon-root">Lr</span>;
const ICON_OBJ_LR_TYPE_COLLECTION = <i className="rmlicon-gallery" />;
const ICON_OBJ_LR_TYPE_FOLDER = <i className="rmlicon-archive" />;

hooks.register('tree/node', node => {
    switch (node.properties.type) {
        case 10:
            node.icon = ICON_OBJ_LR_TYPE_ROOT;
            break;
        case 11:
            node.icon = ICON_OBJ_LR_TYPE_COLLECTION;
            break;
        case 12:
            node.icon = ICON_OBJ_LR_TYPE_FOLDER;
            break;
        default: break;
    }
});

hooks.register('rest/button/success/notice/issue3', () => window.location.reload());