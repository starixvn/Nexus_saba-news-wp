import React, { PureComponent } from 'react';
import Match from './Match';
export default class Grid extends PureComponent {
    constructor(props) {
        super(props);
    }

    render() {
        const { matches, league } = this.props;
        return (
            <div className="wc-bg-overflow">
                {
                    matches.map((item, index) => 
                        <Match key={index} match={item} />
                    )
                }
            </div>
        );
    }
}