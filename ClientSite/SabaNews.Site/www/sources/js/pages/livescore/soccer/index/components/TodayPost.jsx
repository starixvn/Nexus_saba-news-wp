import React, { PureComponent } from 'react';



export default class TodayPost extends PureComponent {
    constructor(props) {
        super(props);
    }

    componentDidMount() {
    }
    render() {

        return (
            <div className="livescore-news">
                <div class="block__title">
                    <h2><a>Tin lịch thi đấu bóng đá</a></h2>
                </div>
                <div className="post card-horizontal">
                    <a className="card-horizontal__image hover-image">
                        <img src="https://vncdn.sabasports.com/2023/06/fiorentina-vs-west-ham.jpg" alt="" class="img-res img-block" />
                    </a>
                    <div className="card-horizontal__content">
                        <a className="title">
                            Declan Rice lên tiếng về tương lai sau chức vô địch Conference League
                        </a>
                        <p class="description">Declan Rice vẫn tỏ ra khá e dè về tương lai sau khi cùng West Ham lên ngôi vô địch Europa Conference League.</p>
                        <p class="datetime">
                            <span>5 giờ trước</span>
                        </p>
                    </div>
                </div>
            </div>
        );
    }
}
