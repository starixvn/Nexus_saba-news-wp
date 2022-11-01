import React, { PureComponent } from 'react';
import Item from './Item';


export default class Grid extends PureComponent {
    constructor(props) {
        super(props);
    }


    render() {
        const { matches } = this.props;
        return (
            <div className="statistic-grid">
                {
                    matches.map((item, index) => <Item key={index} number={index} match={item} />)
                }
            </div>
        );
    }
}
