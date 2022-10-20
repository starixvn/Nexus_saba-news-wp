import React, { PureComponent } from 'react';
import Item from './Item';


export default class Grid extends PureComponent {
    constructor(props) {
        super(props);
    }


    render() {
        const { matches } = this.props;
        return (
            <div className="soccer-match-grid-item">
                <div className="soccer-match-grid-item-body">
                {
                    matches.map((item, index) => <Item key={index} number={index} match={item} />)
                }
                </div>
            </div>
        );
    }
}
