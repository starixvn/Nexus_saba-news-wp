import React, { PureComponent } from 'react';


export default class LiveScore extends PureComponent {
    constructor(props) {
        super(props);
    }
    render () {
        const { matches } =  this.props;
        if(!matches) return null
        console.log(matches)
        return (
            <>
                {
                    matches.map(item => {
                        return (
                            <div className="soccer-match-grid" key={item.key}>
                                <div className="soccer-match-grid-item">
                                    <div className="soccer-match-grid-item-header">{ item.text }</div>
                                    <div className="soccer-match-grid-item-body">
                                        {
                                            item.childs.map(child => <div key={child.key} className="group-match-item">
                                            <div className="group-match-item-slogan">{child.text}</div>
                                             asdsdasa
                                            </div>
                                            
                                            )
                                        }
                                    </div>
                                </div>
                            </div>
                        )
                    })
                }
            </>
        )
    }
}   