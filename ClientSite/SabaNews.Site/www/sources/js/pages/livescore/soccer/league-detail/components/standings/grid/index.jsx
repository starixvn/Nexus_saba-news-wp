import React, { PureComponent } from 'react';
import THead from './THead';
import TBody from './TBody';

export default class Index extends PureComponent {
    getGroupName = groupName => `Bảng ${groupName.charAt(0)}`;

    render() {
        const { standings, isMobile } = this.props;
        const totalItems = standings.length;

        return (
            <>
                {
                    standings.map((group, index) => {
                        return (
                            <div key={index} className="table-custom">
                                <THead groupName={totalItems > 1 ? this.getGroupName(group.key) : 'Đội'} isMobile={isMobile} />
                                <TBody group={group} isMobile={isMobile} />
                            </div>
                        );
                    })
                }
            </>
        );
    }
}