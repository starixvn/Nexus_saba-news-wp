import React, { PureComponent } from 'react';
import TableHead from './table/Head';
import TableBody from './table/Body';

const endItem = 6;
export default class GridItem extends PureComponent {
    constructor(props) {
        super(props);
        this.totalItem = props.standings.length - 1;
        this.state = {
            selectedKey: props.standings[0].key,
            startItem: 0,
            endItem: endItem
        }
    }

    handleSelectedGroup = key => {
        this.setState({selectedKey: key});
    };

    handleNextTab = () => {
        const { startItem, endItem } = this.state;
        if(endItem < this.totalItem) {
            this.setState({startItem: startItem + 1, endItem: endItem  + 1});
        }
    };

    handlePrevTab = () => {
        const { startItem, endItem } = this.state;
        if(startItem > 0) {
            this.setState({startItem: startItem - 1, endItem: endItem  - 1});
        }
    };


    render() {
        const { startItem, endItem, selectedKey } = this.state;
        const standings = this.props.standings.slice(startItem, endItem);
        const selectedGroup = this.props.standings.find(item => item.key === selectedKey);
        return (
            <div className="group-standing">
                <ul className="homepage-group-standing">
                <li className={`btn-btn-standing prev-standing ${startItem === 0 ? 'disabled': '' }`} onClick={this.handlePrevTab}><i className="icon-chevron-left" /></li>
                    {
                        standings.map((group, index) => {
                            const groupName = group.key.charAt(0);
                            return (
                                <li onClick={() => this.handleSelectedGroup(group.key)} className="group" key={index}>{groupName}</li>
                            )
                        })
                    }
                    <li  className={`btn-btn-standing next-standing ${endItem >= this.totalItem ? 'disabled': '' }`} onClick={this.handleNextTab}><i className="icon-chevron-right" /></li>
                </ul>
                <div className="table-custom">
                    <TableHead groupName={selectedGroup.key.charAt(0)} isMobile={true} />
                    <TableBody teams={selectedGroup.childs} isMobile={true} />
                </div>
                {/* <span className={`btn-btn-standing prev-standing ${startItem === 0 ? 'disabled': '' }`} onClick={this.handlePrevTab}><i className="icon-chevron-left" /></span>
                <Tabs  activeKey={selectedTab} onSelect={this.handleSelectTab}  id="livescore-tab-league-detail"
                    className="live-score-nav"
                >
                    {
                        standings.map((group, index) => {
                            const groupName = group.key.charAt(0);
                            return (
                                <Tab title={groupName} eventKey={group.key} key={index}>
                                    <div className="table-custom">
                                        <TableHead groupName={groupName} isMobile={true} />
                                        <TableBody teams={group.childs} isMobile={true} />
                                    </div>
                                </Tab>
                            );
                        })
                    }
                </Tabs>
                <span className={`btn-btn-standing next-standing ${endItem >= this.totalItem ? 'disabled': '' }`} onClick={this.handleNextTab}><i className="icon-chevron-right" /></span> */}
            </div>
        );
    }
}
